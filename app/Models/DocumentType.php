<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'allowed_mimes',
        'max_size',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }
}
