<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            $target = route('home', absolute: false).'?verified=1';
            if ($request->user()->is_admin) {
                $target = url('/admin/products').'?verified=1';
            }

            return redirect()->intended($target);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $target = route('home', absolute: false).'?verified=1';
        if ($request->user()->is_admin) {
            $target = url('/admin/products').'?verified=1';
        }

        return redirect()->intended($target);
    }
}
