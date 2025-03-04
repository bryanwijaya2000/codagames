<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    use HasFactory;
    protected $table = 'games';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
    function gameOwner() {
        return $this->belongsTo(Users::class, "user_id", "id");
    }
}
