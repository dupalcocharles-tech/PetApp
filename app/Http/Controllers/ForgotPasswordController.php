<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\PetOwner;
use App\Models\PasswordResetOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showRequestForm($role)
    {
        if ($role === 'clinic_staff') {
            $role = 'clinic';
        }
        return view('auth.passwords.email', compact('role'));
    }

    public function findAccount(Request $request)
    {
        // Normalize role before validation
        if ($request->role === 'clinic_staff') {
            $request->merge(['role' => 'clinic']);
        }

        $request->validate([
            'role' => 'required|in:clinic,pet_owner',
            'identifier' => 'required|string',
        ]);

        $role = $request->role;
        $identifier = $request->identifier;

        // Try exact match first (email, phone, or username)
        $query = ($role === 'clinic') ? Clinic::query() : PetOwner::query();
        
        $user = $query->where(function($q) use ($identifier) {
            $q->where('email', $identifier)
              ->orWhere('phone', $identifier)
              ->orWhere('username', $identifier);
        })->first();

        // If not found and identifier looks like a phone number, try normalized versions
        if (!$user && preg_match('/^[0-9+ \-]+$/', $identifier)) {
            $digits = preg_replace('/[^0-9]/', '', $identifier);
            
            // Try variations of PH numbers
            $variations = [$digits];
            if (str_starts_with($digits, '0')) {
                $variations[] = '63' . substr($digits, 1);
                $variations[] = substr($digits, 1);
            } elseif (str_starts_with($digits, '63')) {
                $variations[] = '0' . substr($digits, 2);
                $variations[] = substr($digits, 2);
            } elseif (strlen($digits) === 10) {
                $variations[] = '0' . $digits;
                $variations[] = '63' . $digits;
            }

            $user = (($role === 'clinic') ? Clinic::query() : PetOwner::query())
                ->whereIn('phone', array_unique($variations))
                ->first();
        }

        if (!$user) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'We could not find an account with that information.']);
            }
            return back()->with('message', 'We could not find an account with that information.');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'role' => $role,
                'masked_email' => $this->maskEmail($user->email),
                'masked_phone' => $this->maskPhone($user->phone),
            ]);
        }

        return redirect()->route('password.confirm', ['id' => $user->id, 'role' => $role]);
    }

    public function showConfirmAccount(Request $request, $id)
    {
        $role = $request->query('role');
        $user = ($role === 'clinic') ? Clinic::findOrFail($id) : PetOwner::findOrFail($id);

        $maskedEmail = $this->maskEmail($user->email);
        $maskedPhone = $this->maskPhone($user->phone);

        return view('auth.passwords.confirm', compact('user', 'role', 'maskedEmail', 'maskedPhone'));
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'role' => 'required|in:clinic,pet_owner',
            'method' => 'required|in:sms',
        ]);

        $user = ($request->role === 'clinic') ? Clinic::findOrFail($request->user_id) : PetOwner::findOrFail($request->user_id);
        
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $otpEntry = PasswordResetOtp::create([
            'user_id' => $user->id,
            'user_type' => $request->role,
            'identifier' => $user->phone,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $this->sendSms($user->phone, "Your password reset code is: {$otpCode}. It expires in 5 minutes.");

        return redirect()->route('password.verifyForm', ['id' => $otpEntry->id]);
    }

    public function showVerifyOtp($id)
    {
        $otpEntry = PasswordResetOtp::findOrFail($id);
        return view('auth.passwords.verify', compact('otpEntry'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_id' => 'required|exists:password_resets_otp,id',
            'otp_code' => 'required|string|size:6',
        ]);

        $otpEntry = PasswordResetOtp::findOrFail($request->otp_id);

        if ($otpEntry->is_verified) {
             return redirect()->route('password.changeForm', ['id' => $otpEntry->id]);
        }

        if ($otpEntry->isExpired()) {
            return back()->withErrors(['otp_code' => 'The OTP has expired. Please request a new one.']);
        }

        if ($otpEntry->attempt_count >= 3) {
            return back()->withErrors(['otp_code' => 'Too many failed attempts. Please request a new OTP.']);
        }

        if ($otpEntry->otp_code !== $request->otp_code) {
            $otpEntry->increment('attempt_count');
            $remaining = 3 - $otpEntry->attempt_count;
            return back()->withErrors(['otp_code' => "Incorrect OTP. Remaining attempts: {$remaining}"]);
        }

        $otpEntry->update(['is_verified' => true]);

        return redirect()->route('password.changeForm', ['id' => $otpEntry->id]);
    }

    public function showChangePassword($id)
    {
        $otpEntry = PasswordResetOtp::findOrFail($id);
        if (!$otpEntry->is_verified) {
            return redirect()->route('password.verifyForm', ['id' => $id]);
        }
        return view('auth.passwords.reset', compact('otpEntry'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'otp_id' => 'required|exists:password_resets_otp,id',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // at least one uppercase
                'regex:/[0-9]/',      // at least one number
                'regex:/[@$!%*#?&]/', // at least one special character
            ],
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
        ]);

        $otpEntry = PasswordResetOtp::findOrFail($request->otp_id);
        if (!$otpEntry->is_verified) {
            return redirect()->route('password.verifyForm', ['id' => $otpEntry->id]);
        }

        $user = ($otpEntry->user_type === 'clinic') ? Clinic::find($otpEntry->user_id) : PetOwner::find($otpEntry->user_id);
        
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Invalidate OTP
        $otpEntry->delete();

        return redirect()->route('auth.login', ['role' => $otpEntry->user_type])
                         ->with('success', 'Password reset successfully. You can now login.');
    }

    private function maskEmail($email)
    {
        if (!$email) return '';
        $parts = explode("@", $email);
        $name = $parts[0];
        $domain = $parts[1];
        return substr($name, 0, 1) . str_repeat("*", 5) . "@" . $domain;
    }

    private function maskPhone($phone)
    {
        if (!$phone) return '';
        $len = strlen($phone);
        if ($len < 4) return $phone;
        return substr($phone, 0, 2) . str_repeat("*", $len - 5) . substr($phone, -3);
    }

    private function sendSms($phone, $message)
    {
        // Normalize PH phone number
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '63' . substr($phone, 1);
        } elseif (str_starts_with($phone, '9')) {
            $phone = '63' . $phone;
        }

        try {
            $apiToken = '72ae7e3a8c3ed0e97111f3b5a81887f4ae96a344';
            Http::withoutVerifying()
                ->timeout(60)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://www.iprogsms.com/api/v1/sms_messages', [
                    'api_token'    => $apiToken,
                    'phone_number' => $phone,
                    'message'      => $message,
                ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMS OTP Error: ' . $e->getMessage());
        }
    }
}
