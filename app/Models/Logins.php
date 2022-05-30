<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logins extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = [
        "user_id",
        "device_token",
        "device_brand",
        "device_model",
        "app_version",
        "os_version"
    ];

    public function users()
    {
        return $this->belongsTo(Users::class, "user_id");
    }
}
