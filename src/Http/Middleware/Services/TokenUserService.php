<?php namespace RpbeAuth\Http\Middleware\Services;

use Illuminate\Contracts\Routing\Middleware;

class TokenUserService implements Middleware {

    private $hasher;

    private $user;

    private $authToken;

    private $encrypter;

    /**
     * @return \Illuminate\Encryption\Encrypter
     */
    public function getEncrypter()
    {
        return $this->encrypter;
    }

    /**
     * @param mixed $encrypter
     */
    public function setEncrypter( $encrypter )
    {
        $this->encrypter = $encrypter;
    }



    /**
     * @return \RpbeAuth\Http\Middleware\Model\AuthToken
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * @param mixed $authToken
     */
    public function setAuthToken( $authToken )
    {
        $this->authToken = $authToken;
    }

    /**
     * @return \RpbeAuth\Http\Middleware\Auth\Sha512Hasher
     */
    public function getHasher()
    {
        return $this->hasher;
    }

    /**
     * @param mixed $hasher
     */
    public function setHasher( $hasher )
    {
        $this->hasher = $hasher;
    }

    /**
     * @return \RpbeAuth\Http\Middleware\Model\User;
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser( $user )
    {
        $this->user = $user;
    }


    public function createToken()
    {
        $id = $this->getUser()->id;
        $this->getUser()->forceDelete();
        $this->getAuthToken()->public_key           = $this->getHasher()->make( $this->getHasher()->randomString(20),array( 'key' => \Config::get('app.key') ) );
        $this->getAuthToken()->private_key          = $this->getHasher()->make($this->getAuthToken()->public_key, array( 'key' => \Config::get('app.key') )  );
        $this->getAuthToken()->auth_identifier      = $id;
        $this->getAuthToken()->save();
    }

    public function getPublicToken()
    {

        $payload = $this->getEncrypter()->encrypt(
            array(
                'id' => $this->getUser()->id,
                'key' => $this->getAuthToken()->public_key
            )
        );

        $payload = str_replace(array('+', '/', '\r', '\n', '='), array('-', '_'), $payload);
        return $payload;
    }


    public function handle( $request, \Closure $next)
    {
        $this->setAuthUser();
        if( !\Auth::check() )
        {
            return \Response::json( array( 'error' => 'You are not authorized!' ),  401);
        }
        return $next( $request );
    }

    private function deserialize( $payload )
    {
        try {
            $payload = str_replace( array( '-', '_' ), array( '+', '/' ), $payload );
            $data = $this->getEncrypter()->decrypt( $payload );
        } catch ( \Exception  $e) {
            return \Response::json( array( 'error' => 'You are not authorized!' ), 401 );
        }
        if(empty($data['id']) || empty($data['key']) )
        {
            return \Response::json( array( 'error' => 'You are not authorized!' ),  401);
        }

        $this->getAuthToken()->private_key      = substr( $this->getHasher()->make( $data['key'] ,array( 'key' => \Config::get('app.key') ) ) , 0, 96);
        $this->getAuthToken()->public_key       = substr( $data['key'], 0, 96 );
        $this->getAuthToken()->auth_identifier  = $data['id'];
    }

    private function setAuthUser()
    {
        $xAuthToken = \Request::header( 'X-Auth-Token');
        $this->deserialize($xAuthToken);
        $checkToken = $this->getAuthToken();

        $storeToken = $this->getAuthToken()
            ->where( 'auth_identifier', $checkToken->auth_identifier)
            ->where( 'public_key', $checkToken->public_key)
            ->where( 'private_key', $checkToken->private_key)
            ->first();

        if( $storeToken === null ) {
            return \Response::json( array( 'error' => 'You are not authorized!' ),  401);
        }

        \Auth::setUser( $this->getUser()->find($checkToken->auth_identifier) );
    }

}