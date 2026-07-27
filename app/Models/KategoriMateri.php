<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriMateri extends Model
{
    protected $fillable = ['nama', 'slug'];

    public function materis()
    {
        return $this->hasMany(MateriEdukasi::class);
    }
}
