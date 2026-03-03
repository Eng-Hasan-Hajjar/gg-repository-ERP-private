@extends('layouts.app')
@section('title','طلبات الميديا')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>طلبات الميديا</h4>
    <a href="{{ route('media.create') }}" class="btn btn-namaa">
        طلب جديد
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>الدبلومة</th>
                    <th>تصاميم</th>
                    <th>إعلان</th>
                    <th>دعوة</th>
                    <th>محتوى</th>
                    <th>بودكاست</th>
                    <th>تقييمات</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $r)
                <tr>
                    <td>{{ $r->title }}</td>
                    <td>{{ $r->diploma?->name ?? '-' }}</td>

                    <td><input type="checkbox" disabled {{ $r->design_done ? 'checked':'' }}></td>
                    <td><input type="checkbox" disabled {{ $r->ad_done ? 'checked':'' }}></td>
                    <td><input type="checkbox" disabled {{ $r->invitation_done ? 'checked':'' }}></td>
                    <td><input type="checkbox" disabled {{ $r->content_done ? 'checked':'' }}></td>
                    <td><input type="checkbox" disabled {{ $r->podcast_done ? 'checked':'' }}></td>
                    <td><input type="checkbox" disabled {{ $r->reviews_done ? 'checked':'' }}></td>

                    <td>{{ $r->notes }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection