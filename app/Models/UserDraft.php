<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDraft extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'email'];
    protected $hidden = [ 'updated_at', 'created_at'];

    public function edits()
    {
        return $this->morphOne(UserDraft::class, "editable");
    }

    public function userEdits(){
        return $this->hasOne(UserEdit::class, 'editable_id', 'id');
    }
}
