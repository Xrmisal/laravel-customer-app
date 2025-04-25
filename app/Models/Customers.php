<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model {
    protected $fillable = [
        "name",
        "email",
        "phone_number",
        "date_of_birth",
    ];
}