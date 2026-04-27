<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LinkController extends Controller
{
    public function index(){
        return response()->json([
            "success" => 1,
            "message" => "Page Working"
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'original_url' => ['required', 'url'],
            'title'        => ['nullable', 'string', 'max:255'],
            'custom_alias' => ['nullable', 'alpha_dash', 'max:100', 'unique:links,custom_alias'],
            'password'     => ['nullable', 'string', 'max:255'],
            'expires_at'   => ['nullable', 'date', 'after:now'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Generate unique short code
        $shortCode = $this->generateUniqueCode();

        // Auto title if not provided
        $title = $request->title ?: $this->generateTitleFromUrl($request->original_url);

        $link = Link::create([
            'user_id'         => Auth::check() ? Auth::id() : null, // null if guest
            'title'           => $title,
            'original_url'    => $request->original_url,
            'short_code'      => $shortCode,
            'custom_alias'    => $request->custom_alias,
            'password'        => $request->password ? Hash::make($request->password) : null,
            'expires_at'      => $request->expires_at,
            'is_active'       => true,
            'clicks_count'    => 0,
            'last_clicked_at' => null,
        ]);

        $slug = $link->custom_alias ?: $link->short_code;

        return response()->json([
            'status'  => true,
            'message' => 'Link created successfully',
            'data'    => [
                'id'         => $link->id,
                'title'      => $link->title,
                'original'   => $link->original_url,
                'short_code' => $link->short_code,
                'alias'      => $link->custom_alias,
                'short_url'  => url($slug),
            ]
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
            str_contains($host, 'twitter')  => 'Twitter Link',
            str_contains($host, 'instagram')=> 'Instagram Link',
            str_contains($host, 'facebook') => 'Facebook Link',
            default => $host ? ucfirst($host) : 'Short Link'
        };
    }
}
