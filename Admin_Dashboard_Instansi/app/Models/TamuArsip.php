<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TamuArsip extends Model
{
    protected $table = 'tamu_arsip';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tanggal_kunjungan' => 'date',
    ];
}
