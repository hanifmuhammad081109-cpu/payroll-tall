<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    protected $table = 'departemen';

    protected $fillable = ['kode', 'nama'];

    /**
     * Relasi One-to-many: 1 Departemen memiliki banyak Jabatan
     */
    public function jabatan()
    {
        return $this->hasMany(Jabatan::class);
    }
}
