<?php namespace RpbeAuth\Http\Middleware\Model;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'auth_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['auth_identifier', 'public_key', 'private_key'];

}