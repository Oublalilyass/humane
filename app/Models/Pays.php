<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    protected $fillable = ['nom'];

    public function villes()
    {
        return $this->hasMany(Ville::class);
    }
}