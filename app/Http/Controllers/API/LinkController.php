<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $links = Link::where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $links,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'original_url' => ['required', 'url'],
            'title' => ['nullable', 'string', 'max:255'],
            'custom_alias' => ['nullable', 'alpha_dash', 'max:100', 'unique:links,custom_alias'],
            'password' => ['nullable', 'string', 'max:255'],
            'expires_at' => ['nullable', 'date', 'after:now'],
            'guest_id' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Auth check
        $user = Auth::user();

        // Guest ID logic (IMPORTANT)
        if ($user) {
            $userId = $user->id;
            $ownerType = 'authenticated';
            $guestId = null;
        } else {
            $userId = null;
            $ownerType = 'guest';
            $guestId = $request->guest_id ?? (string) Str::ulid();
        }

        // short code
        $shortCode = $this->generateUniqueCode();

        // title fallback
        $title = $request->title ?: $this->generateTitleFromUrl($request->original_url);

        $link = Link::create([
            'user_id' => $userId,   // logged in user else null
            'guest_id' => $guestId,     // guest tracking
            'title' => $title,
            'original_url' => $request->original_url,
            'short_code' => $shortCode,
            'custom_alias' => $request->custom_alias,
            'password' => $request->password ? Hash::make($request->password) : null,
            'expires_at' => $request->expires_at,
            'is_active' => true,
            'clicks_count' => 0,
        ]);

        $slug = $link->custom_alias ?: $link->short_code;

        return response()->json([
            'status' => true,
            'message' => 'Link created successfully',
            'data' => [
                'id' => $link->id,
                'title' => $link->title,
                'original' => $link->original_url,
                'short_url' => url($slug),
                'owner_type' => $ownerType,
                // IMPORTANT for frontend persistence
                'user_id' => $user?->id,     // For loggedin users
                'guest_id' => $guestId,       // For not loggedin users
            ],
        ], 201);
    }

    private function generateUniqueCode()
    {
        do {
            $code = Str::random(6);
        } while (Link::where('short_code', $code)->exists());

        return $code;
    }

    private function generateTitleFromUrl($url)
    {
        $host = parse_url($url, PHP_URL_HOST);

        return match (true) {
            str_contains($host, 'youtube') => 'YouTube Link',
            str_contains($host, 'twitter') => 'Twitter Link',
            str_contains($host, 'instagram') => 'Instagram Link',
            str_contains($host, 'facebook') => 'Facebook Link',
            str_contains($host, 'medium') => 'Medium Link',
            default => $host ? ucfirst($host) : 'Short Link'
        };
    }
}
