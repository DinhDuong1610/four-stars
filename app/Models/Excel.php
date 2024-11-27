<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Excel extends Model
{
    use SoftDeletes;

    public function folder() {
        return $this->belongsTo(Folder::class);
    }
}
