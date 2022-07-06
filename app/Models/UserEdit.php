<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEdit extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'editable_id', 'editable_type', 'maker_id', 'checker_id', 'status', 'request_type'];
    public function editable()
    {
        return $this->morphTo();
    }
}
