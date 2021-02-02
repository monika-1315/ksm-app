<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'receiver_group' => 'int',
        'division' => 'int',
        'author' => 'int',
        'modified' => 'int'
    ];
    
}
