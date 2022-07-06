<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEdit extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'editable_id', 'editable_type', 'maker_id', 'checker_id', 'status', 'request_type'];

    protected $hidden = ['user_id', 'editable_id', 'maker_id', 'checker_id', 'created_at', 'updated_at'];

    public function editable()
    {
        return $this->morphTo();
    }

    public function maker()
    {
        return $this->belongsTo(Admin::class, 'maker_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
