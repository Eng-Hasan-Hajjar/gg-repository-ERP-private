<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaRequest extends Model
{
    protected $fillable = [
        'user_id',
        'diploma_id',

        'requester_name',
        'requester_phone',

        'diploma_name',
        'diploma_code',
        'trainer_name',
        'trainer_location',
        'trainer_photography_available',
        'certificate_accreditation',
        'customer_service_responsible',
        'diploma_location',

        'details_file',
        'trainer_image',

        'need_ad',
        'need_invitation',
        'need_review_video',
        'need_content',
        'need_podcast',
        'need_carousel',
        'need_other',

        'design_done',
        'ad_done',
        'invitation_done',
        'content_done',
        'podcast_done',
        'reviews_done',

        'notes'
    ];

    protected $casts = [
        'trainer_photography_available' => 'boolean',
        'need_ad' => 'boolean',
        'need_invitation' => 'boolean',
        'need_review_video' => 'boolean',
        'need_content' => 'boolean',
        'need_podcast' => 'boolean',
        'need_carousel' => 'boolean',
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