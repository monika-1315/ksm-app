<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
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
        'event_id' => 'int',
        'user_id' => 'int',
        'visible' => 'int',
        'is_sure' => 'int',
        'want_messages' => 'int'
    ];
    
}
