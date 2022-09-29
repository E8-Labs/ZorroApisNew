<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserProfile extends Model
{
    
    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
}
