<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private function redirectToFrontLogin()
    {
        return redirect()->to(route('hexavers') . '#login');
    }

    private function getSessionUser(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser) {
            return null;
        }

        return User::find($sessionUser->id);
    }

    // login page

    public function showLoginForm()
    {
        return view('hexavers');
    }
    // login

    public function login(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request->input('email', '')))
        ]);

        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->redirectToFrontLogin()
                ->withErrors($e->validator)
                ->withInput()
                ->with('active_form', 'loginForm');
        }

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->redirectToFrontLogin()
                    ->with('error', 'Invalid email or password.')
                    ->with('active_form', 'loginForm');
            }

            $request->session()->put('user', $user);

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'instructor' => redirect()->route('instructor.dashboard'),
                'rider' => redirect()->route('rider.dashboard'),
                default => redirect()->route('customer.dashboard'),
            };
        } catch (\Exception $e) {
            return $this->redirectToFrontLogin()
                ->with('error', 'Login encountered an issue. Please try again later.')
                ->with('active_form', 'loginForm');
        }
    }
    // logout

    public function logout(Request $request)
    {
        $request->session()->forget('user');

        return redirect()->route('hexavers');
    }
    // dashb

    public function adminDashboard()
    {
        return view('admin');
    }

    public function riderDashboard()
    {
        return view('riders');
    }

    public function riderDelivery()
    {
        return view('rider.delivery');
    }

    public function customerDashboard()
    {
        return view('customer');
    }

    // rider adm
    public function storeRider(Request $request)
    {
        $admin = $this->getSessionUser($request);

        if (!$admin || $admin->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|max:50',
            'vehicle' => 'nullable|max:100', // optional
        ]);

        try {
            // Save rider in the database
            $rider = User::create([
                'name' => $data['name'],
                'email' => strtolower(trim($data['email'])),
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => 'rider',
                'address' => $data['vehicle'] ?? null, // temporarily store vehicle in address field
            ]);

            // Generate Rider ID for display
            $riderId = 'R-' . str_pad($rider->id, 3, '0', STR_PAD_LEFT);

            return response()->json([
                'ok' => true,
                'rider' => [
                    'id' => $riderId,
                    'name' => $rider->name,
                    'email' => $rider->email,
                    'tempPassword' => $data['password'],
                    'phone' => $rider->phone,
                    'status' => 'Available',
                    'vehicle' => $data['vehicle'] ?? '',
                    'last' => 'Newly added',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create rider. Check connection and retry.'], 500);
        }
    }

    public function listRiders(Request $request)
    {
        $admin = $this->getSessionUser($request);
        if (!$admin || $admin->role !== 'admin') {
            return response()->json([], 403);
        }

        $riders = User::where('role', 'rider')->get()->map(function ($r) {
            return [
                'id' => 'R-' . str_pad($r->id, 3, '0', STR_PAD_LEFT),
                'name' => $r->name,
                'email' => $r->email,
                'phone' => $r->phone,
                'status' => 'Available',
                'vehicle' => $r->address ?? '',
                'last' => 'N/A',
            ];
        });

        return response()->json($riders);
    }

    // cus prf page
    public function customerProfile(Request $request)
    {
        $user = $this->getSessionUser($request);

        if (!$user) {
            return $this->redirectToFrontLogin()
                ->with('error', 'Please login first.');
        }

        return view('customer.profile', compact('user'));
    }

    // updt prf

    public function updateCustomerProfile(Request $request)
    {
        $user = $this->getSessionUser($request);

        if (!$user) {
            return $this->redirectToFrontLogin()
                ->with('error', 'Please login again.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:user,email,' . $user->id,
            'phone' => 'nullable|max:50',
            'address' => 'nullable|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $imageName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('images/profiles'), $imageName);
            $validated['profile_picture'] = 'images/profiles/' . $imageName;
        }

        $user->update($validated);

        $request->session()->put('user', $user);

        return redirect()->route('customer.profile')
            ->with('success', 'Profile updated successfully.');
    }

    // rider prf
    public function riderProfile(Request $request)
    {
        $user = $this->getSessionUser($request);

        if (!$user || $user->role !== 'rider') {
            return $this->redirectToFrontLogin()
                ->with('error', 'Please login as a rider.');
        }

        return view('rider.profile', compact('user'));
    }

    // rider prof upt
    public function updateRiderProfile(Request $request)
    {
        $user = $this->getSessionUser($request);

        if (!$user || $user->role !== 'rider') {
            return $this->redirectToFrontLogin()
                ->with('error', 'Please login as a rider.');
        }

        // Validate rider-specific fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'emergency' => 'nullable|string|max:50',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $imageName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('images/profiles'), $imageName);
            $validated['profile_picture'] = 'images/profiles/' . $imageName;
        }

        // Update user and refresh session
        $user->update($validated);
        $request->session()->put('user', $user);

        return redirect()->route('rider.profile')
            ->with('success', 'Profile updated successfully.');
    }

    // rider prof upt pass
    public function updateRiderPassword(Request $request)
    {
        $user = $this->getSessionUser($request);

        if (!$user || $user->role !== 'rider') {
            return $this->redirectToFrontLogin()
                ->with('error', 'Please login as a rider.');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('rider.profile')
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('rider.profile')
            ->with('success', 'Password updated successfully.');
    }

    // upt pass

    public function updateCustomerPassword(Request $request)
    {
        $user = $this->getSessionUser($request);

        if (!$user) {
            return $this->redirectToFrontLogin()
                ->with('error', 'Please login again.');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('customer.profile')
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.profile')
            ->with('success', 'Password updated successfully.');
    }

    // reg
    public function register(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request->input('email', '')))
        ]);

        try {
            $data = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|min:6|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->redirectToFrontLogin()
                ->withErrors($e->validator)
                ->withInput()
                ->with('active_form', 'registerForm');
        }

        try {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'customer',
            ]);

            return $this->redirectToFrontLogin()
                ->with('success', 'Account created successfully! Please login.')
                ->with('active_form', 'loginForm');
        } catch (\Exception $e) {
            return $this->redirectToFrontLogin()
                ->with('error', 'Registration failed. Please check your connection and try again.')
                ->with('active_form', 'registerForm');
        }
    }
    // forg pass

    public function forgotpassPost(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request->input('email', '')))
        ]);

        try {
            $request->validate([
                'email' => 'required|email|exists:user,email',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->redirectToFrontLogin()
                ->withErrors($e->validator)
                ->withInput()
                ->with('active_form', 'forgotPass');
        }

        $user = User::where('email', $request->email)->first();

        $token = Str::random(60);

        try {
            $user->update([
                'password_reset_token' => $token,
                'token_expires_at' => now()->addHour(),
            ]);

            $resetUrl = route('reset.password.form', ['token' => $token]);

            Mail::to($user->email)->send(new ResetPasswordMail($resetUrl));

            return $this->redirectToFrontLogin()
                ->with('success', 'Password reset instructions sent to your email.')
                ->with('active_form', 'forgotPass');

        } catch (\Exception $e) {
            if ($user) {
                $user->update(['password_reset_token' => null, 'token_expires_at' => null]);
            }
            return $this->redirectToFrontLogin()
                ->with('error', 'Failed to send email. Please check your internet connection.')
                ->with('active_form', 'forgotPass');
        }
    }

    // reset pass view

    public function showResetForm($token)
    {
        $user = User::where('password_reset_token', $token)
            ->where('token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return $this->redirectToFrontLogin()
                ->with('error', 'Invalid or expired reset token.');
        }

        return view('reset-password', compact('token'));
    }

    // res pass

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('password_reset_token', $request->token)
            ->where('token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return $this->redirectToFrontLogin()
                ->with('error', 'Invalid or expired reset token.');
        }

        try {
            $user->update([
                'password' => Hash::make($request->password),
                'password_reset_token' => null,
                'token_expires_at' => null,
            ]);

            return $this->redirectToFrontLogin()
                ->with('success', 'Password successfully reset! You can now login.');
        } catch (\Exception $e) {
            return $this->redirectToFrontLogin()
                ->with('error', 'Failed to save new password. Please retry.');
        }
    }

    public function manageRidersPage(Request $request)
    {
        $user = $this->getSessionUser($request);

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('hexavers')
                ->with('error', 'Please login as admin.');
        }

        return view('admin.manage-riders');
    }
}
