<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function UpdateProfile(Request $request)
    {

        $auth = auth('api')->user();
        $userId = $auth->id;

        if (!$auth) {
            return response()->json(['Anda belum terdaftar']);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::updateOrCreate(
            ['id' => $userId], // Kondisi untuk mencocokkan data yang ada berdasarkan ID
            [
                'full_name' => $request->has('full_name') ? $request->full_name : $auth->full_name,
            ]

        );

        $photo = $user->photo;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            CloudinaryStorage::delete($photo);
            $result = CloudinaryStorage::upload($file->getRealPath(), $file->getClientOriginalName());
            $user->update(['photo' => $result]);
        }
        $user->save();

        return response()->json($user);
    }

    public function DeleteProfile($id)
    {

        $auth = auth('api')->user();
        $authId = $auth->id;
        // dd($authId);

        if (!$auth) {
            return response()->json(['Anda belum terdaftar']);
        }

        $userId = $id;
        // dd($userId);

        if ($userId != $authId) {
            return response()->json(['message' => 'Ini Bukan Akun Anda']);
        }
        $user = User::find($userId);
        CloudinaryStorage::delete($user->photo);
        $user->update(['photo' => 'https://bit.ly/default-photo']);
        return response()->json(['message' => 'berhasil dihapus']);
    }

    
}
