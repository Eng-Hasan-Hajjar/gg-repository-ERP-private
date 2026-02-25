@extends('layouts.app')
@section('title','الأرباح حسب البرنامج')

@section('content')

<div class="glass-card">

    <div class="section-header">
        <i class="bi bi-graph-up"></i>
        الأرباح حسب البرنامج
    </div>

    <div class="p-4">

        @forelse($report as $data)

            <div class="glass-card mb-4 p-4">

                <h5 class="fw-bold mb-4">
                    {{ $data['diploma']->name ?? '-' }}
                </h5>

                <div class="row g-3">

                    @foreach($data['currencies'] as $currency => $amount)

                        <div class="col-md-4">
                            <div class="p-3 border rounded-4 h-100">

                                <div class="text-muted small mb-1">
                                    العملة
                                </div>

                                <div class="fw-bold fs-5">
                                    {{ $currency }}
                                </div>

                                <hr>

                                <div class="fw-bold fs-4 text-success">
                                    {{ number_format($amount,2) }}
                                </div>

                            </div>
                        </div>

                    @endforeach

                </div>

            </div>

        @empty

            <div class="alert alert-warning mb-0">
                لا يوجد بيانات
            </div>

        @endforelse

    </div>

</div>

@endsection