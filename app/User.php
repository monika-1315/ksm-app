<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property integer $Id
 * @property string $Email
 * @property string $Haslo
 * @property string $Imie
 * @property string $Nazwisko
 * @property date $DataUr
 * @property integer $OddzialId
 * @property boolean $CzyZautoryzowane
 * @property boolean $CzyKierownictwo
 * @property boolean $CzyZarzad
 */

class User extends Authenticatable implements JWTSubject
{
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['Email', 'Haslo', 'Imie', 'Nazwisko', 'DataUr', 'OddzialId', 'CzyZautoryzowane', 'CzyKierownictwo', 'CzyZarzad'];


    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function contacts()
    // {
    //     return $this->hasMany('App\Contact');
    // }


    public static function create(Request $request)
    {
        $user = new User();

        if (!empty($request->get('Email'))) {
            $user->Email = $request->get('Email');
        }
        if (!empty($request->get('Haslo'))) {
            $user->Haslo = bcrypt($request->get('Haslo'));
        }

        $user->save();

        return $user;
    }



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->Haslo;
    }
}