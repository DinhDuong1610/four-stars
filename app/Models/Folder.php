<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    public function parent() {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function excels() {
        return $this->hasMany(Excel::class);
    }
}
