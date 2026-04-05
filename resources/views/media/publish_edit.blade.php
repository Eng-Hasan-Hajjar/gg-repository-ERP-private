@extends('layouts.app')
@section('title','تعديل سجل نشر')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">تعديل سجل النشر #{{ $publish->id }}</h4>
    <a href="{{ route('media.publish.index') }}" class="btn btn-soft">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('media.publish.update', $publish) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-bold">ربط بطلب ميديا (اختياري)</label>
                <select name="media_request_id" class="form-select">
                    <option value="">— بدون ربط —</option>
                    @foreach($mediaRequests as $mr)
                        <option value="{{ $mr->id }}" {{ $publish->media_request_id == $mr->id ? 'selected' : '' }}>
                            #{{ $mr->id }} — {{ $mr->diploma_name ?? $mr->requester_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">الدبلومة <span class="text-danger">*</span></label>
                <input type="text" name="diploma_name" class="form-control"
                       value="{{ old('diploma_name', $publish->diploma_name) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">المحتوى <span class="text-danger">*</span></label>
                <select name="content_category" class="form-select" required>
                    <option value="ad" {{ $publish->content_category == 'ad' ? 'selected' : '' }}>إعلان</option>
                    <option value="invitation" {{ $publish->content_category == 'invitation' ? 'selected' : '' }}>دعوة</option>
                    <option value="content" {{ $publish->content_category == 'content' ? 'selected' : '' }}>محتوى</option>
                    <option value="review" {{ $publish->content_category == 'review' ? 'selected' : '' }}>تقييم</option>
                    <option value="general_content" {{ $publish->content_category == 'general_content' ? 'selected' : '' }}>محتوى عام</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">نوع المحتوى <span class="text-danger">*</span></label>
                <select name="content_type" class="form-select" required>
                    <option value="design" {{ $publish->content_type == 'design' ? 'selected' : '' }}>تصميم</option>
                    <option value="video" {{ $publish->content_type == 'video' ? 'selected' : '' }}>فيديو</option>
                    <option value="carousel" {{ $publish->content_type == 'carousel' ? 'selected' : '' }}>كاروسيل</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">الفرع</label>
                <input type="text" name="branch" class="form-control"
                       value="{{ old('branch', $publish->branch) }}">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">كابشن</label>
                <textarea name="caption" class="form-control" rows="3">{{ old('caption', $publish->caption) }}</textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">تاريخ النشر</label>
                <input type="date" name="publish_date" class="form-control"
                       value="{{ old('publish_date', $publish->publish_date?->format('Y-m-d')) }}">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">تم النشر</label>
                <div class="d-flex gap-4 flex-wrap">
                    <div class="form-check">
                        <input type="checkbox" name="published_meta" class="form-check-input" id="pubMeta"
                               {{ $publish->published_meta ? 'checked' : '' }}>
                        <label class="form-check-label" for="pubMeta">
                            <i class="bi bi-meta"></i> نشر ميتا
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="published_tiktok" class="form-check-input" id="pubTiktok"
                               {{ $publish->published_tiktok ? 'checked' : '' }}>
                        <label class="form-check-label" for="pubTiktok">
                            <i class="bi bi-tiktok"></i> نشر تيك توك
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="published_youtube" class="form-check-input" id="pubYoutube"
                               {{ $publish->published_youtube ? 'checked' : '' }}>
                        <label class="form-check-label" for="pubYoutube">
                            <i class="bi bi-youtube"></i> نشر يوتوب
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-namaa">
                    <i class="bi bi-check2-circle"></i> تحديث سجل النشر
                </button>
            </div>

        </div>
    </form>
</div>

@endsection