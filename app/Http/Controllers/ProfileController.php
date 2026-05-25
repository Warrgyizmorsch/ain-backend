<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\LoginHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function profile()
    {

        return view('profile.edit');
    }


    public function updateProfile(Request $request, $id)
    {
        // dd($request); 
        try {
            $user = User::findOrFail($id);
    
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->mobile_no = $request->input('phone');
            $user->address = $request->input('address');
    
            if ($request->hasFile('photo')) {
                $uploadedFile = $request->file('photo');
    
                $fileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
                $destinationPath = public_path('assets/media/avatars');
    
                $uploadedFile->move($destinationPath, $fileName);
    
                $user->photo = 'assets/media/avatars/' . $fileName;
            } else {
                $user->photo = 'assets/media/avatars/blank.png';
            }
    
            $user->save();
    
            return redirect()->back()->with('success', 'Profile Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function password()
    {
      
        return view('auth.profile.change-password', compact('premission')); 
    }


    public function changePassword(Request $request)
    {
        return view('users.change-password');
    }
 
    public function changePasswordSave(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:8|string',
        ]);
    
        $auth = Auth::user();
    
        // Check if the current password is valid
        if (!Hash::check($request->get('current_password'), $auth->password)) {
            return redirect()->back()->with('error', 'Current Password is Invalid');
        }
    
        // Check if the new password is the same as the current password
        if (strcmp($request->get('current_password'), $request->new_password) == 0) {
            return redirect()->back()->with('error', 'New Password cannot be the same as your current password.');
        }
    
        // Update the user's password
        $user = User::find($auth->id);
        $user->password = Hash::make($request->new_password);
        $user->save();
    
        // Add a success message and redirect back
        return redirect()->back()->with('success', 'Password Changed Successfully');
    }
    public function changePasswordSaveAdmin(Request $request)
    {
        $this->validate($request, [
            'current_password_admin' => 'required|string',
            'new_password' => 'required|min:8|string',
            'new_password_confirmation' => 'required|min:8|string',
            'user_id' => 'required|exists:users,id'
        ]);
    
        $auth = Auth::user();
        

        // Retrieve the payload
        $payload = request()->all();
        // Retrieve the current user's ID
        $userId = $payload['user_id'];
        // Retrieve the user record from the database
        $user = User::find($userId);

        // Verify if the new password and its confirmation are not empty
        if (empty($payload['current_password_admin']) || empty($payload['new_password']) || empty($payload['new_password_confirmation'])) {
            // Passwords are empty, redirect back with an error message
            
            return response()->json(['message' => 'password cannot be empty']);
        }
        
        
        // Verify if the new password and its confirmation match
        if ($payload['new_password'] !== $payload['new_password_confirmation']) {
            // Passwords don't match, redirect back with an error message
            
            return response()->json(['message' => 'New password and confirmation do not match']);
            
        }

        // Verify if the current password matches the hashed password stored in the database
        if (Hash::check($payload['current_password_admin'], $auth->password)) {
            // Hash the new password
            $hashedNewPassword = Hash::make($payload['new_password']);

            // Update the user's password
            $user->update(['password' => $hashedNewPassword]);

            // Password updated successfully, redirect back with a success message
           
            return response()->json(['message' => 'Password updated successfully'], 200);
        } else {
            // Current password doesn't match, redirect back with an error message
            
            return response()->json(['message' => 'Current password of Admin is incorrect']);

        }
    
    }
}
