<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Exception;
use Laravel\Socialite\Facades\Socialite;

class ProviderRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider)
    {
        if(!in_array($provider, ['google', 'facebook'])){
            return redirect(route('login'))->withErrors(['provider' => 'Invalid provider']);
        }
       
        try {
            return Socialite::driver($provider)->redirect();
        } catch (Exception $e) {
            return redirect(route('login'))->withErrors(['provider' => 'Something went wrong']);
        }
    }
}
