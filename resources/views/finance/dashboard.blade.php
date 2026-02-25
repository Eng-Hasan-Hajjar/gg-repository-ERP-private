@extends('layouts.app')
@section('title','لوحة المالية')

@section('content')

<div class="glass-card">

    <div class="section-header">
        <i class="bi bi-speedometer2"></i>
        لوحة التحكم المالية
    </div>

    <div class="p-4">

        <h5 class="fw-bold mb-3">إجمالي شامل</h5>

        <div class="row g-3">

            @foreach($totalTotals as $currency => $row)

                <div class="col-md-4">

                    <div class="glass-card p-3 text-center">

                        <div class="fw-bold mb-2">
                            {{ $currency }}
                        </div>

                        <div class="text-success fw-bold">
                            دخل: {{ number_format($row['in'],2) }}
                        </div>

                        <div class="text-danger fw-bold">
                            مصاريف: {{ number_format($row['out'],2) }}
                        </div>

                        <hr>

                        <div class="fw-bold fs-5">
                            الصافي: {{ number_format($row['in'] - $row['out'],2) }}
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>

@endsection