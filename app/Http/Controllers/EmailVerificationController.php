<?php

namespace App\Http\Controllers;

use App\Notifications\EmailVerificationNotification;
use Cache;
use Illuminate\Http\Request;
use App\Models\User;
class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $email = $request->input('email');
        $token = $request->input('token');
        if (!$email || !$token) {
            throw new \Exception('Verify link is incorrect');
        }
        if ($token != Cache::get('email_verification_'.$email)) {
            throw new \Exception('Verify link in correct or expired');
        }
        if (!$user = User::where('email', $email)->first()) {
            throw new \Exception('User does not exist');
        }
        Cache::forget('email_verification_'.$email);
        $user->update(['email_verified' => true]);
        return view('pages.success', ['msg' => 'Email Verified Successfully']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function send(Request $request)
    {
        $user = $request->user();
        if ($user->email_verified){
            return view('pages.success', ['msg' => 'Email Verified Successfully']);
        }
        $user->notify(new EmailVerificationNotification());
        return view('pages.success', ['msg' => 'Email is sent successfully']);

    }
}
