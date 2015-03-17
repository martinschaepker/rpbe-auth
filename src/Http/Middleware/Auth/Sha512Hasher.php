<?php

namespace RpbeAuth\Http\Middleware\Auth;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

/**
  * SHA512 Implementation for Laravels Standard HashInterface
  */
class Sha512Hasher implements HasherContract
{

    /**
     * Create a new Sha512 hasher instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Create a new Random
     *
     * @return string
     */
    public function randomString( $length, $special = false )
    {
        $result   = '';
        $char     = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $specialc = array('*', '#', '+', '~', '(', ')', '/', '-', '_', ',', '.', '%', '$', '§', '&', '{', '}', '=', '[', ']', '|', '<', '>');

        for ( $i = 0; $i < $length; $i++ ) {
          if( $special && rand( 0, 1 ) )
            $result .= $specialc[rand(0, (count($specialc) -1))];
          else
            $result .= $char[rand(0, (count($char) - 1))];
        }

        return $result;
    }

    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @return array   $options
     * @return string
     */
    public function make( $value, array $options = array() )
    {
        $salt = $options['key'];
         return hash( 'sha512', $value.$salt ).$salt;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function check( $value, $hashedValue, array $options = array() )
    {
        $salt = substr($hashedValue, 128);

        return ( hash( 'sha512', $value.$salt).$salt ) === $hashedValue;
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string  $hashedValue
     * @param  array   $options
     * @return bool
     */
    public function needsRehash( $hashedValue, array $options = array() )
    {
        if( strlen($hashedValue) === 128 )

            return false;

        return true;
    }
}
