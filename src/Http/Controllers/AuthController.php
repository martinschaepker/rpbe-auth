<?php namespace RpbeAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AuthController extends Controller {

    public function login()
    {
        var_dump(   \Auth::check() );
        var_dump( \Auth::attempt(array(
            'email' => 'test@tredex.de',
            'password' => 'test123',
            'active'=> 1
        )));
        echo "<pre>";
        var_dump(\DB::getQuerylog());
        echo "<pre>";
    }
}