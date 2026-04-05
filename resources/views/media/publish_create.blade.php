@extends('layouts.app')
@section('title','إضافة سجل نشر')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">إضافة سجل نشر جديد</h4>
    <a href="{{ route('media.publish.index') }}" class="btn btn-soft">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('media.publish.store') }}">
        @csrf

        <div class="row g-3">

            {{-- ربط بطلب ميديا (اختياري) --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">ربط بطلب ميديا (اختياري)</label>
                <select name="media_request_id" class="form-select">
                    <option value="">— بدون ربط —</option>
                    @foreach($mediaRequests as $mr)
                        <option value="{{ $mr->id }}" {{ old('media_request_id') == $mr->id ? 'selected' : '' }}>
                            #{{ $mr->id }} — {{ $mr->diploma_name ?? $mr->requester_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- الدبلومة --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">الدبلومة <span class="text-danger">*</span></label>
                <input type="text" name="diploma_name" class="form-control"
                       value="{{ old('diploma_name') }}" required>
            </div>

            {{-- المحتوى --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">المحتوى <span class="text-danger">*</span></label>
                <select name="content_category" class="form-select" required>
                    <option value="">اختر...</option>
                    <option value="ad" {{ old('content_category') == 'ad' ? 'selected' : '' }}>إعلان</option>
                    <option value="invitation" {{ old('content_category') == 'invitation' ? 'selected' : '' }}>دعوة</option>
                    <option value="content" {{ old('content_category') == 'content' ? 'selected' : '' }}>محتوى</option>
                    <option value="review" {{ old('content_category') == 'review' ? 'selected' : '' }}>تقييم</option>
                    <option value="general_content" {{ old('content_category') == 'general_content' ? 'selected' : '' }}>محتوى عام</option>
                </select>
            </div>

            {{-- نوع المحتوى --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">نوع المحتوى <span class="text-danger">*</span></label>
                <select name="content_type" class="form-select" required>
                    <option value="">اختر...</option>
                    <option value="design" {{ old('content_type') == 'design' ? 'selected' : '' }}>تصميم</option>
                    <option value="video" {{ old('content_type') == 'video' ? 'selected' : '' }}>فيديو</option>
                    <option value="carousel" {{ old('content_type') == 'carousel' ? 'selected' : '' }}>كاروسيل</option>
                </select>
            </div>

            {{-- الفرع --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">الفرع</label>
                <input type="text" name="branch" class="form-control"
                       value="{{ old('branch') }}" placeholder="اسم الفرع">
            </div>

            {{-- كابشن --}}
            <div class="col-12">
                <label class="form-label fw-bold">كابشن</label>
                <textarea name="caption" class="form-control" rows="3"
                          placeholder="نص الكابشن للنشر...">{{ old('caption') }}</textarea>
            </div>

            {{-- تاريخ النشر --}}
            <div class="col-md-4">
                <label class="form-label fw-bold">تاريخ النشر</label>
                <input type="date" name="publish_date" class="form-control"
                       value="{{ old('publish_date') }}">
            </div>

            {{-- تم النشر --}}
            <div class="col-12">
                <label class="form-label fw-bold">تم النشر</label>
                <div class="d-flex gap-4 flex-wrap">
                    <div class="form-check">
                        <input type="checkbox" name="published_meta" class="form-check-input" id="pubMeta">
                        <label class="form-check-label" for="pubMeta">
                            <i class="bi bi-meta"></i> نشر ميتا (فيسبوك / انستغرام)
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="published_tiktok" class="form-check-input" id="pubTiktok">
                        <label class="form-check-label" for="pubTiktok">
                            <i class="bi bi-tiktok"></i> نشر تيك توك
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="published_youtube" class="form-check-input" id="pubYoutube">
                        <label class="form-check-label" for="pubYoutube">
                            <i class="bi bi-youtube"></i> نشر يوتوب
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-namaa">
                    <i class="bi bi-check2-circle"></i> حفظ سجل النشر
                </button>
            </div>

        </div>
    </form>
</div>

@endsection