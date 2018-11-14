<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Message;
use App\Contact;
class Status extends Model
{
    //
    protected $guarded = [];

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function message()
    {
        return $this->belongsTo('App\Message');
    }
}
