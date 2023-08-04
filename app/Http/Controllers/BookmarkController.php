<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookmarkController extends Controller
{
    public function CreateBookmark(Request $request, $username, $id)
    {

        $auth = auth('api')->user();
        $authId = $auth->id;
        if (!$auth) {
            return response()->json(['Anda belum terdaftar']);
        }
        
        $user = User::where('username', $username)->first();
        $userId = $user->id;
        
        $place = Place::where('id',$id)->where('user_id',$userId)->first();
        $placeId = $place->id;
        // dd($placeId);

        if (!$place) {
            return response()->json(['Username bukan pemilik dari toko ini']);
        }

        $bookmarked = DB::table('bookmarks')
            ->where('user_id', $authId)
            ->where('place_id', $placeId)
            ->first();

        if ($bookmarked) {
            if ($bookmarked->bookmark == false) {
                DB::table('bookmarks')
                    ->where('user_id', $authId)
                    ->where('place_id', $placeId)
                    ->update(['bookmark' => true]);

                $message = 'bookmark successfully.';
            } else {
                DB::table('bookmarks')
                    ->where('user_id', $authId)
                    ->where('place_id', $placeId)
                    ->update(['bookmark' => false]);

                $message = 'bookmark removed successfully.';
            }
        } else {
            DB::table('bookmarks')
                ->insert([
                    'user_id' => $authId,
                    'place_id' => $placeId,
                    'bookmark' => true,
                ]);

            $message = 'bookmarkd successfully.';
        }

        $totalbookmarks = DB::table('bookmarks')
            ->where('place_id', $placeId)
            ->where('bookmark', true)
            ->count();


        return response()->json([
            'total_bookmarks' => $totalbookmarks,
        ]);
    }
}
