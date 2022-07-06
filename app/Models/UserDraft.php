<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDraft extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];


    public function edits()
    {
        return $this->morphOne(UserDraft::class, "editable");
    }
}
