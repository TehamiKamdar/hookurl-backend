<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user) {

            $links = Link::where('user_id', $user->id)
                ->latest()
                ->get();

            $ownerType = 'authenticated';

        } else {

            // COOKIE READ
            $guestId = $request->cookie('guest_id');

            if ($guestId) {

                $links = Link::where('guest_id', $guestId)
                    ->latest()
                    ->get();

            } else {

                $links = [];

            }

            $ownerType = 'guest';
        }

        return response()->json([
            'status' => true,
            'message' => 'Links fetched successfully',
            'owner_type' => $ownerType,
            'data' => $links,
        ], 200);
    }
}
