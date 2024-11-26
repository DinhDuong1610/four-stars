<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Excel extends Model
{
    public function folder() {
        return $this->belongsTo(Folder::class);
    }
}
