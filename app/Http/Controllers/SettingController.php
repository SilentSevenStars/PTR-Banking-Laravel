<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index() {
        return view('profile.setting');
    }

    public function changeEmail(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email,'.Auth::id()],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findorFail(Auth::user()->id);
        $user->email = $request->email;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required'],
            'new_password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'same:new_password'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findorFail(Auth::user()->id);
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['errors' => ['old_password' => ['Old password is incorrect.']]], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true]);
    }

    public function disconnect($provider) {
        $user = User::findorFail(Auth::user()->id);
        if ($provider === 'google') {
            $user->google_id = null;
            $user->google_token = null;
            $user->google_refresh_token = null;
        } elseif ($provider === 'facebook') {
            $user->facebook_id = null;
            $user->facebook_token = null;
            $user->facebook_refresh_token = null;
        }
        $user->save();

        return response()->json(['success' => true]);
    }
}
