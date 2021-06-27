<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenerimaModel extends Model
{
    protected $table = 'penerima';
    protected $fillable = [
        'name', 'email', 'kontak','alamat','keterangan'
    ];
}
