@extends('layouts.app')
@php($activeModule = 'programs')

@section('title', 'إدارة البرامج')

@section('content')

   <div class="d-flex justify-content-between align-items-center mb-3">

<h4 class="fw-bold">

@if(request('type') == 'online')
إدارة البرامج الأونلاين

@elseif(request('type') == 'onsite')
إدارة البرامج الحضورية

@else
إدارة جميع البرامج
@endif

</h4>

</div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>اسم الدبلومة</th>
                        <th>السعر</th>
                        <th>الحملة</th>
                        <th class="text-end">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>{{ $r->diploma->name }}</td>
                            <td>{{ $r->price ?? '-' }}</td>
                            <td>
                                {{ $r->campaign_start ?? '-' }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('programs.management.edit', $r->diploma_id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    إدارة
                                </a>


                                <a href="{{ route('programs.management.show', $r->diploma) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    لوحة البرنامج
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                لا يوجد بيانات
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection