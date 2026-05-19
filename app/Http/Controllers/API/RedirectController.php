<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function handle($code){
        $link = Link::where('custom_alias' , $code)->orWhere('short_code' , $code)->first();

        if(!$link){
            abort(404);
        }

        return redirect()->away($link->original_url);
    }
}
