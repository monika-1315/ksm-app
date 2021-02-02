<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
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
        'division' => 'int',
        'author' => 'int'
    ];
    
    
}
