<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $primaryKey = 'msv';

    protected $keyType = 'string'; 

    public $incrementing = false; 

    protected $fillable = [
        'msv',
        'last_name',
        'first_name',
        'birth',
        'email',
        'sc_class',
        'score',
        'note',
        'folder_id',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
