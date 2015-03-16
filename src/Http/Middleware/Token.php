<?php namespace RpbeAuth\Http\Middleware;


use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class Token extends BaseVerifier
{

    public function handle( $request, Closure $next )
    {
        if( strpos( $request->getPathInfo(), '/login' ) === false ) {
            if( \Auth::check() === false ) {
                return \Response::json(
                    array( 'error' => 'You are not authorized!' ),
                    401
                );
            }
        }
        return $next( $request );
    }
}