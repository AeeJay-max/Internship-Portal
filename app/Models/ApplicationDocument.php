<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationDocument extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'application_id',
        'document_type_id',
        'cert_name',
        'file_path',
        'is_verified',
        'uploaded_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'uploaded_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
