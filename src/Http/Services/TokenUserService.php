<?php namespace RpbeAuth\Http\Services;

class TokenUserService {

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
        $this->getAuthToken()->private_key = $this->getHasher()->make ($this->getUser()->email );
        $this->getAuthToken()->public_key = $this->getHasher()->make ($this->getUser()->email );
        $this->getAuthToken()->auth_identifier = $this->getUser()->id;
        $this->getAuthToken()->save();
    }

    public function getPublicToken()
    {
        $payload = $this->getEncrypter()->encrypt(
            array(
                'id' => $this->getUser()->id,
                'key' => $this->getUser()->public_key
            )
        );
        $payload = str_replace(array('+', '/', '\r', '\n', '='), array('-', '_'), $payload);
        return $payload;
    }

}