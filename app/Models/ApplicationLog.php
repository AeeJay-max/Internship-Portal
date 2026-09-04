<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    protected $fillable = [
        'application_id',
        'action',
        'performed_by',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Log a single action. Call this everywhere an important event happens.
     *
     * Usage:  ApplicationLog::log($application->id, 'Application submitted', Auth::id());
     */
    public static function log(int $applicationId, string $action, ?int $performedBy = null): self
    {
        return self::create([
            'application_id' => $applicationId,
            'action'         => $action,
            'performed_by'   => $performedBy,
        ]);
    }
}
