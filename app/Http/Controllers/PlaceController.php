<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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


    public function ReadAllPlace()
    {
        $auth = auth('api')->user();
        if (!$auth) {
            return response()->json(['Anda belum terdaftar']);
        }
        $authId = $auth->id;

        $dataUser = User::select('username', 'photo')->where('id', $authId)->get();

        $user = DB::table('places')
            ->join('users', 'places.user_id', '=', 'users.id')
            ->select(
                'users.username',
                'users.phone_number',
                'places.*',
                DB::raw('(SELECT COUNT(*) FROM bookmarks WHERE place_id = places.id AND bookmark = true) as bookmark_count')
            )
            ->get();

        $response = [
            'user' => $user,
            'login' => $dataUser,
        ];

        // Kembalikan respon dengan semua tempat (places)
        return response()->json($response);
    }

    public function ReadDetailPlace($username, $id)
    {

        $auth = auth('api')->user();
        $authId = $auth->id;

        if (!$auth) {
            return response()->json(['Anda belum terdaftar']);
        }

        $user = User::where('username', $username)->first();
        $userId = $user->id;

        $user = DB::table('places')
            ->join('users', 'places.user_id', '=', 'users.id')
            ->where('places.id', $id)
            ->select('users.username', 'users.photo', 'places.*')
            ->get();

        $bookmark = DB::table('bookmarks')
            ->where('place_id', $id)
            ->where('user_id', $authId)
            ->first();

        $response = [
            'user' => $user,
            'bookmark_status' => $bookmark && $bookmark->bookmark ? 'true' : 'false'
        ];

        return response()->json($response);



        return response()->json(['error' => 'User tidak ditemukan'], 401);
    }

    private function geocodeAddress($address)
    {
        $base_url = "https://nominatim.openstreetmap.org/search?";
        $params = http_build_query([
            'q' => $address,
            'format' => 'json',
            'limit' => 1,
        ]);
        $client = new Client();
        $response = $client->get($base_url . $params);
        $data = json_decode($response->getBody(), true);
        // dd($data);
        
        if (!empty($data)) {
            return ['latitude' => $data[0]['lat'], 'longitude' => $data[0]['lon']];
        } else {
            return null;
        }
    }

    private function findNearest($latitude, $longitude)
    {
        $radius = 10; // Jarak dalam kilometer
        $earthRadius = 6371; // Radius bumi dalam kilometer

        $place = Place::selectRaw('*, 
        (' . $earthRadius . ' * acos(cos(radians(' . $latitude . ')) 
        * cos(radians(`lat`)) * cos(radians(`long`) 
        - radians(' . $longitude . ')) + sin(radians(' . $latitude . ')) 
        * sin(radians(`lat`)))) AS distance')
            ->having('distance', '<', $radius)
            ->orderBy('distance', 'asc')
            ->first();

        return $place;
    }

    public function search(Request $request)
    {
        $address = $request->input('address');

        if (!$address) {
            return response()->json(['error' => 'Address is required.'], 400);
        }

        // Lakukan geocoding alamat menggunakan Nominatim
        $coordinates = $this->geocodeAddress($address);

        if (!$coordinates) {
            return response()->json(['error' => 'Invalid address.'], 400);
        }

        $latitude = $coordinates['latitude'];
        $longitude = $coordinates['longitude'];

        $place = $this->findNearest($latitude, $longitude);

        if (!$place) {
            return response()->json(['error' => 'No masjid found nearby.'], 404);
        }

        return response()->json($place);
    }
}
