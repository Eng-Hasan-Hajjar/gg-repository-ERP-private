<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaRequest extends Model
{
    protected $fillable = [
        'user_id',
        'diploma_id',
        'title',
        'description',
        'design_done',
        'ad_done',
        'invitation_done',
        'content_done',
        'podcast_done',
        'reviews_done',
        'notes'
    ];

    protected $casts = [
        'design_done' => 'boolean',
        'ad_done' => 'boolean',
        'invitation_done' => 'boolean',
        'content_done' => 'boolean',
        'podcast_done' => 'boolean',
        'reviews_done' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diploma()
    {
        return $this->belongsTo(Diploma::class);
    }

    public function schedules()
    {
        return $this->hasMany(MediaSchedule::class);
    }
}