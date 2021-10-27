<?php

namespace App\Models\DB2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $connection = "db_servidor";
    protected $table = "video";
}
