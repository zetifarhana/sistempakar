<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gangguan extends Model
{
    protected $table = 'tbl_gangguan';
    protected $primaryKey = 'kode_gangguan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['kode_gangguan', 'nama_gangguan'];

}
