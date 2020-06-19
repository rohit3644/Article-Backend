<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// contacts table with the mutators
class Contact extends Model
{
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = htmlspecialchars(stripslashes(trim($value)));
    }
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = htmlspecialchars(stripslashes(trim($value)));
    }
    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = htmlspecialchars(stripslashes(trim($value)));
    }
}
