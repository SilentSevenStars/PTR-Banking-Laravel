<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider)
    {
        if(!in_array($provider, ['google', 'facebook'])){
            return redirect(route('login'))->withErrors(['provider' => 'Invalid provider']);
        }
        $socialUser = Socialite::driver($provider)->user();

        if($provider === 'google'){
            $user = User::updateOrCreate([
                'google_id' => $socialUser->id
            ], [
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'google_token' => $socialUser->token,
                'google_refresh_token' => $socialUser->refreshToken,
            ]);
        } elseif($provider === 'facebook'){
            $user = User::updateOrCreate([
                'facebook_id' => $socialUser->id
            ], [
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'facebook_token' => $socialUser->token,
                'facebook_refresh_token' => $socialUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect('/');
    }
}
