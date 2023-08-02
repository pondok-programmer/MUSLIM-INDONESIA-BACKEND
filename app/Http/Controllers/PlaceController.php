<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlaceController extends Controller
{
    public function CreatePlace(Request $request)
    {
        $auth = auth('api')->user();
        $userId = $auth->id;
        if (!$auth) {
            return response()->json(['Anda belum terdaftar']);
        }
        //     elseif ($auth->role !== 'Admin') {
        //     return response()->json(['message' => 'kamu bukan admin']);
        // }

        $validator = Validator::make($request->all(), [
            'place_name' => 'required|string|max:255',
            'categories' => 'required|string|max:13|unique:places',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'province' => 'required|string',
            'regency' => 'required|string',
            'district' => 'required|string',
            'deskripsi' => 'required|string',
            'village' => 'required|string',
            'addres' => 'required|string',
            'lat' => 'required|string|unique:places',
            'long' => 'required|string|unique:places',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $photo  = $request->file('photo');
        $result = CloudinaryStorage2::upload($photo->getRealPath(), $photo->getClientOriginalName());
        // dd($result);


        $place = Place::create(

            [
                'user_id' => $userId,
                'place_name' => $request->place_name,
                'categories' => $request->categories,
                'photo' => $result,
                'deskripsi_photo' => $request->deskripsi_photo,
                'province' => $request->province,
                'regency' => $request->regency,
                'district' => $request->district,
                'village' => $request->village,
                'addres' => $request->addres,
                'lat' => $request->lat,
                'long' => $request->long,
            ]

        );
        $place->save();

        return response()->json(['Toko Berhasil Ditambah']);
    }

    public function DeletePlace($id)
    {
        $auth = auth('api')->user();
        if (!$auth) {
            return response()->json(['Anda belum terdaftar']);
        }

        $place = Place::find($id);
        if (!$place) {
            return response()->json(['error' => 'Perusahaan tidak ditemukan']);
        } else {
            CloudinaryStorage2::delete($place->photo);
            $place->delete();
        }

        return response()->json(['Toko berhasil Dihapus']);
    }

    public function UpdatePlace(Request $request, $id)
    {
        $auth = auth('api')->user();
        $userId = $auth->id;
        if (!$auth) {
            return response()->json(['Anda belum terdaftar']);
        }

        $validator = Validator::make($request->all(), [
            'place_name' => 'nullable|string|max:255',
            'categories' => 'nullable|string|max:13|unique:places',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'province' => 'nullable|string',
            'regency' => 'nullable|string',
            'district' => 'nullable|string',
            'deskripsi_photo' => 'nullable|string',
            'village' => 'nullable|string',
            'addres' => 'nullable|string',
            'lat' => 'nullable|string|unique:places',
            'long' => 'nullable|string|unique:places',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $existingPlace = Place::find($id);

        $photo = $existingPlace->photo;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            CloudinaryStorage::delete($photo);
            $result = CloudinaryStorage::upload($file->getRealPath(), $file->getClientOriginalName());
            $existingPlace->update(['photo' => $result]);
        }

        $place = Place::updateOrCreate(
            ['id' => $existingPlace->id], // Kondisi untuk mencocokkan data yang ada berdasarkan ID
            [
                'place_name' => $request->has('place_name') ? $request->place_name : $existingPlace->place_name,
                'categories' => $request->has('categories') ? $request->categories : $existingPlace->categories,
                'province' => $request->has('province') ? $request->province : $existingPlace->province,
                'regency' => $request->has('regency') ? $request->regency : $existingPlace->regency,
                'district' => $request->has('district') ? $request->district : $existingPlace->district,
                'deskripsi_photo' => $request->has('deskripsi_photo') ? $request->deskripsi_photo : $existingPlace->deskripsi_photo,
                'village' => $request->has('village') ? $request->village : $existingPlace->village,
                'addres' => $request->has('addres') ? $request->addres : $existingPlace->addres,
                'lat' => $request->has('lat') ? $request->lat : $existingPlace->lat,
                'long' => $request->has('long') ? $request->long : $existingPlace->long,
            ]

        );

        $place->save();

        return response()->json(["Data Berhasil Diubah"]);
    }
}
