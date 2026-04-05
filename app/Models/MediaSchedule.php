<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaSchedule extends Model
{
    protected $fillable = [
        'media_request_id',
        'title',
        'post_type',
        'publish_at',
        'content_link',
    ];

    protected $casts = [
        'publish_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(MediaRequest::class, 'media_request_id');
    }

    public function getPostTypeLabelAttribute(): string
    {
        return match ($this->post_type) {
            'design'          => 'تصميم',
            'ad_video'        => 'فيديو إعلان',
            'content_video'   => 'فيديو محتوى',
            'reviews'         => 'تقييمات',
            'invitation'      => 'دعوة',
            'general_content' => 'محتوى عام',
            default           => '-',
        };
    }
}