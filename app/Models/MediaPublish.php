<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaPublish extends Model
{
    protected $table = 'media_publishes';

    protected $fillable = [
        'media_request_id',
        'diploma_name',
        'content_category',
        'content_type',
        'branch',
        'caption',
        'published_meta',
        'published_tiktok',
        'published_youtube',
        'publish_date',
    ];

    protected $casts = [
        'published_meta'    => 'boolean',
        'published_tiktok'  => 'boolean',
        'published_youtube' => 'boolean',
        'publish_date'      => 'date',
    ];

    public function mediaRequest()
    {
        return $this->belongsTo(MediaRequest::class);
    }

    public function getContentCategoryLabelAttribute(): string
    {
        return match ($this->content_category) {
            'ad'              => 'إعلان',
            'invitation'      => 'دعوة',
            'content'         => 'محتوى',
            'review'          => 'تقييم',
            'general_content' => 'محتوى عام',
            default           => '-',
        };
    }

    public function getContentTypeLabelAttribute(): string
    {
        return match ($this->content_type) {
            'design'   => 'تصميم',
            'video'    => 'فيديو',
            'carousel' => 'كاروسيل',
            default    => '-',
        };
    }
}