<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testing extends Model
{
    protected $table = 'testings';

    protected $fillable = [
        'name',
        'image',
    ];
}
