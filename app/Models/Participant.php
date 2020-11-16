<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model {
    
    protected $table = 'participant';

    // protected $fillable = ['klub', 'pemain'];

    // protected $guarded = ['PARTICIPANT_ID'];
    protected $hidden = [
        'PARTICIPANT_ID', 'PASSWORD', 'VERIFICATION_KEY'
    ];
    
}