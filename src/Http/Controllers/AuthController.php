<?php namespace RpbeAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use RpbeAuth\Http\Middleware\Services\TokenUserService;

class AuthController extends Controller {

    private $tokenUserService;

    /**
     * @return \RpbeAuth\Http\Middleware\Services\TokenUserService;
     */
    public function getTokenUserService()
    {
        return $this->tokenUserService;
    }

    /**
     * @param mixed $tokenUserService
     */
    public function setTokenUserService( $tokenUserService )
    {
        $this->tokenUserService = $tokenUserService;
    }

    public function __construct( TokenUserService $tokenUserService )
    {
        $this->setTokenUserService( $tokenUserService );
    }

    public function login()
    {
        if( \Auth::check() || ( \Auth::attempt( array(
                'email'    => \Input::get( 'email' ),
                'password' => \Input::get( 'password' ),
                'active'   => 1
            ), false ) ) )
        {

            $this->getTokenUserService()->setUser( \Auth::user() );
            $this->getTokenUserService()->createToken( );
            $publicToken = $this->getTokenUserService()->getPublicToken();

            $user = \Auth::user();

            return \Response::json( array( 'token'                   => $publicToken,
                                           'userid'                  => \Auth::user()->getAuthIdentifier(),
                                           'selectedProductLanguage' => $user->product_language,
                                           'channel'                 => $user->channel
            ), 200 );
        }
        return \Response::json( array( 'error' => 'You are not authorized!' ), 401 );
    }
}