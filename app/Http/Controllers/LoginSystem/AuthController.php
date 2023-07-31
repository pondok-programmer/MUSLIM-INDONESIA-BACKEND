<?php

namespace App\Http\Controllers\LoginSystem;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendEmailVerifyJob;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'phone_number' => [
                'required',
                'string',
                'regex:/^(?:\+?62|0)(?:\d{8,15})$/',
            ],
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors()
            ]);
        }
        $users = User::create([
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // $verification = URL::temporarySignedRoute(
        //     'verification.verify',
        //     now()->addMinutes(60),
        //     ['id' => $users->id, 'hash' => sha1($users->getEmailForVerification())]
        // );

        // $SendEmailVerifyJob = new SendEmailVerifyJob($users, $verification);
        // dispatch($SendEmailVerifyJob);

        return response()->json([
            'Massage' => 'userCreatedSuccessfully',
            'user' => $users
        ]);
    }



    public function registerAdmin(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors()
            ]);
        }
        $users = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin'

        ]);

        $verification = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $users->id, 'hash' => sha1($users->getEmailForVerification())]
        );

        $SendEmailVerifyJob = new SendEmailVerifyJob($users, $verification);
        dispatch($SendEmailVerifyJob);

        return response()->json([
            'Massage' => 'userCreatedSuccessfully',
        ]);
    }
}
