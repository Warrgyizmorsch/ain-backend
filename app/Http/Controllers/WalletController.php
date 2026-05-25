<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    // -------------------------
    // FRONTEND WALLET (as is)
    // -------------------------
 public function show()
    {
        return view('frontend.wallet', [
            'walletUser'    => null,          // koi user select nahi
            'walletAmount'  => null,          // null => "£ --"
            'transactions'  => collect(),     // empty history
            'walletEmail'   => null,          // form prefill ke liye
        ]);
    }

    /**
     * Email verify → user + wallet + history
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');

        // 1) User dhundo
        $walletUser = User::where('email', $email)->first();

        if (! $walletUser) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'No account found with this email. Please check and try again.',
                ]);
        }

        // 2) Transaction history (sirf table dikhane ke liye)
        $transactions = WalletTransaction::where('user_id', $walletUser->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // 3) Wallet amount logic (TERI DEMAND KE HISAB SE)
        //    - Primary: users.wallet column
        //    - Agar null ya missing ho, tab hi fallback: credits - debits
        if (!is_null($walletUser->wallet)) {
            $walletAmount = $walletUser->wallet;  // yahi final amount dikhana hai
        } else {
            // fallback (agar future me kabhi null mila)
            $credits = WalletTransaction::where('user_id', $walletUser->id)
                ->where('type', 'credit')
                ->sum('amount');

            $debits = WalletTransaction::where('user_id', $walletUser->id)
                ->where('type', 'debit')
                ->sum('amount');

            $walletAmount = $credits - $debits;
        }

        return view('frontend.wallet', [
            'walletUser'    => $walletUser,
            'walletAmount'  => $walletAmount,
            'transactions'  => $transactions,
            'walletEmail'   => $email,
        ])->with('success', 'Wallet details fetched successfully.');
    }

    // -------------------------------------
    // COMMON: ek user ko wallet credit karo
    // -------------------------------------
    protected function applyCredit(User $user, float $amount, ?string $desc = null, ?Carbon $expiresAt = null): void
    {
        $desc = $desc ?: 'Wallet credited by admin';

        $currentBalance = $user->wallet ?? 0;
        $newBalance     = $currentBalance + $amount;

        WalletTransaction::create([
            'user_id'       => $user->id,
            'amount'        => $amount,
            'type'          => 'credit',
            'description'   => $desc,
            'balance_after' => $newBalance,
            'expires_at'    => $expiresAt,
        ]);

        $user->wallet = $newBalance;
        $user->save();
    }

    // -------------------------------------
    // ADMIN – BULK CREDIT SCREEN (DATE FILTER)
    // -------------------------------------
    public function showAdminCreditForm(Request $request)
    {
        $filters = [
            'from_date' => $request->input('from_date'),
            'to_date'   => $request->input('to_date'),
            'search'    => $request->input('search'),
        ];

        $usersQuery = User::select('id', 'name', 'email', 'created_at');

        // Search by name/email
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Date filter – user created_at par
        if (!empty($filters['from_date'])) {
            $usersQuery->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $usersQuery->whereDate('created_at', '<=', $filters['to_date']);
        }

        $users = $usersQuery
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('back-end.wallet.bulk-credit', compact('users', 'filters'));
    }

    // -------------------------------------
    // ADMIN – BULK CREDIT SUBMIT
    // -------------------------------------
    public function adminBulkCredit(Request $request)
    {
        $request->validate([
            'user_ids'    => ['required', 'array', 'min:1'],
            'user_ids.*'  => ['integer', 'exists:users,id'],
            'amount'      => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:255'],
            'expires_at'  => ['nullable', 'date'],
        ], [
            'user_ids.required' => 'Please select at least one user.',
        ]);

        $amount    = (float) $request->amount;
        $desc      = $request->description ?: 'Wallet credited by admin (bulk)';
        $expiresAt = $request->expires_at
            ? Carbon::parse($request->expires_at)
            : null;

        $users = User::whereIn('id', $request->user_ids)->get();

        foreach ($users as $user) {
            $this->applyCredit($user, $amount, $desc, $expiresAt);
        }

        return redirect()
            ->route('admin.wallet.bulk-credit.form', $request->only('from_date', 'to_date', 'search'))
            ->with('success', 'Wallet credited for '.count($users).' user(s) successfully.');
    }
}
