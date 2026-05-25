<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrimeassimentController extends Controller
{
    // 1. PRIME ASSIGNMENT LEADS (Read)
   public function showSmartLeads(Request $request)
    {
        $processedLeadIds = DB::table('convert_leads')->pluck('lead_id');

        $query = DB::table('primeassiment')
                   ->whereNotIn('id', $processedLeadIds) // Processed leads hide hongi
                   ->where('source_url', 'like', '%https://smartassignmenthelp.de/%')
                   ->orderBy('id', 'desc');

        if ($request->filled('name')) $query->where('name', 'like', '%' . $request->name . '%');
        if ($request->filled('email')) $query->where('email', 'like', '%' . $request->email . '%');

        $leads = $query->paginate(15)->appends($request->all());
        return view('Smart-Assiment.index', compact('leads')); 
    }

    public function showPrimeLeads(Request $request)
    {
        $processedLeadIds = DB::table('convert_leads')->pluck('lead_id');

        $query = DB::table('primeassiment')
                   ->whereNotIn('id', $processedLeadIds) // Processed leads hide hongi
                   ->where('source_url', 'like', '%https://primeassignmenthelp.co.uk/%')
                   ->orderBy('id', 'desc');

        if ($request->filled('name')) $query->where('name', 'like', '%' . $request->name . '%');
        if ($request->filled('email')) $query->where('email', 'like', '%' . $request->email . '%');

        $leads = $query->paginate(15)->appends($request->all());
        return view('Prime-Assiment.index', compact('leads')); 
    }

    public function showPrimeOrders(Request $request)
    {
        $query = DB::table('primeassiment')
            ->join('convert_leads', 'primeassiment.id', '=', 'convert_leads.lead_id')
            ->where('convert_leads.action_type', 'order')
            ->where('primeassiment.source_url', 'like', '%https://primeassignmenthelp.co.uk/%')
            ->select('primeassiment.*', 'convert_leads.created_at as processed_at')
            ->orderBy('convert_leads.id', 'desc');

        // Filter Logic
        if ($request->filled('name')) {
            $query->where('primeassiment.name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('primeassiment.email', 'like', '%' . $request->email . '%');
        }

        $leads = $query->paginate(15)->appends($request->all());
        return view('orders', compact('leads')); 
    }

    public function showPrimeCancelled()
    {
        $leads = DB::table('primeassiment')
            ->join('convert_leads', 'primeassiment.id', '=', 'convert_leads.lead_id')
            ->where('convert_leads.action_type', 'cancel')
            ->where('primeassiment.source_url', 'like', '%https://primeassignmenthelp.co.uk/%') // Sirf Prime ki URL
            ->select('primeassiment.*', 'convert_leads.message as cancel_reason', 'convert_leads.created_at as processed_at')
            ->orderBy('convert_leads.id', 'desc')
            ->paginate(15);

        // View file ab Prime wale folder se aayegi
        return view('cancelled', compact('leads')); 
    }

    public function showSmartOrders(Request $request)
        {
            $query = DB::table('primeassiment')
                ->join('convert_leads', 'primeassiment.id', '=', 'convert_leads.lead_id')
                ->where('convert_leads.action_type', 'order')
                ->where('primeassiment.source_url', 'like', '%https://smartassignmenthelp.de/%')
                ->select('primeassiment.*', 'convert_leads.created_at as processed_at')
                ->orderBy('convert_leads.id', 'desc');

            // Filter Logic
            if ($request->filled('name')) {
                $query->where('primeassiment.name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('email')) {
                $query->where('primeassiment.email', 'like', '%' . $request->email . '%');
            }

            $leads = $query->paginate(15)->appends($request->all());
            return view('orders', compact('leads')); 
        }

    public function showSmartCancelled()
    {
        $leads = DB::table('primeassiment')
            ->join('convert_leads', 'primeassiment.id', '=', 'convert_leads.lead_id')
            ->where('convert_leads.action_type', 'cancel')
            ->where('primeassiment.source_url', 'like', '%https://smartassignmenthelp.de/%') // Sirf Smart ki URL
            ->select('primeassiment.*', 'convert_leads.message as cancel_reason', 'convert_leads.created_at as processed_at')
            ->orderBy('convert_leads.id', 'desc')
            ->paginate(15);

        // View file ab Smart wale folder se aayegi
        return view('cancelled', compact('leads')); 
    }

    // (Aapke store, update, destroy wale functions yahan same rahenge...)

    // =====================================
    // 2. NEW ACTIONS: Convert & Cancel
    // =====================================
    public function convertToOrder($id)
    {
        DB::table('convert_leads')->insert([
            'lead_id' => $id,
            'action_type' => 'order',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        return redirect()->back()->with('success', 'Lead converted to Order successfully!');
    }

    public function cancelLead(Request $request, $id)
    {
        DB::table('convert_leads')->insert([
            'lead_id' => $id,
            'action_type' => 'cancel',
            'message' => $request->cancel_message,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        return redirect()->back()->with('success', 'Lead has been cancelled.');
    }

    public function store(Request $request)
    {
        DB::table('primeassiment')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country,
            'mobile_number' => $request->mobile_number,
            'services' => $request->services,
            'subject' => $request->subject,
            'work_type' => $request->work_type,
            'select_urgency' => $request->select_urgency,
            'word_count' => $request->word_count,
            'enter_topic' => $request->enter_topic,
            'requirements' => $request->requirements,
            'source_url' => $request->source_url,
            'created_at' => Carbon::now() // Current time save karega
        ]);

        // Form save hone ke baad user ko wapas usi page par bhej dega
        return redirect()->back();
    }

    // =====================================
    // 4. UPDATE LEAD (Purani Lead Edit Karna)
    // =====================================
    public function update(Request $request, $id)
    {
        DB::table('primeassiment')->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country,
            'mobile_number' => $request->mobile_number,
            'services' => $request->services,
            'subject' => $request->subject,
            'work_type' => $request->work_type,
            'select_urgency' => $request->select_urgency,
            'word_count' => $request->word_count,
            'enter_topic' => $request->enter_topic,
            'requirements' => $request->requirements,
            'source_url' => $request->source_url
            // Update me created_at change nahi karte
        ]);

        return redirect()->back();
    }

    public function restoreLead($id)
    {
        // Cancel ki hui lead ko convert_leads table se hata do
        // Isse wo automatically filter se bahar aakar wapas 'New Leads' me dikhne lagegi
        DB::table('convert_leads')
            ->where('lead_id', $id)
            ->where('action_type', 'cancel')
            ->delete();

        return redirect()->back()->with('success', 'Lead restored successfully!');
    }

    // 5. DELETE LEAD
    public function destroy($id)
    {
        DB::table('convert_leads')->where('lead_id', $id)->delete();
        DB::table('primeassiment')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Lead permanently deleted!');
    }
}