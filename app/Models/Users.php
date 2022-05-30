<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $primaryKey = "user_id";
    public $incrementing = false;
    protected $keyType = "string";
    protected $fillable = [
        "user_id",
        "email",
        "first_name",
        "last_name",
        "image_path",
        "gender",
        "dob",
        "theme",
        "type",
        "point"
    ];
    protected $casts = ["email" => "encrypted", "first_name" => "encrypted", "last_name" => "encrypted", "gender" => "encrypted", "dob" => "encrypted"];

    public function logins()
    {
        return $this->hasMany(Logins::class, "user_id");
    }
}
