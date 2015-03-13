<?php namespace RpbeAuth\Http\Middleware;


use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class Token extends BaseVerifier  {

    public function handle( $request, Closure $next )
    {
        if(strpos( $request->getPathInfo(), '/login') === false )
        {
            return parent::handle($request, $next);
        }
        return $next($request);
    }
}