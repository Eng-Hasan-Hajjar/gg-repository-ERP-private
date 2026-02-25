@extends('layouts.app')
@section('title','التقرير اليومي')

@section('content')

<div class="glass-card">

    <div class="section-header">
        <i class="bi bi-calendar-event"></i>
        التقرير اليومي
    </div>

    <div class="p-4">

        <form class="mb-4 d-flex gap-2">
            <input type="date" name="date" value="{{ $date }}" class="form-control w-auto">
            <button class="btn btn-namaa btn-pill">
                عرض
            </button>
        </form>

        <div class="row g-3 mb-4">

            @foreach($totals as $currency => $row)

                <div class="col-md-4">
                    <div class="p-3 border rounded-4 h-100">

                        <div class="fw-bold mb-2">
                            {{ $currency }}
                        </div>

                        <div class="text-success fw-bold">
                            المقبوض: {{ number_format($row['in'],2) }}
                        </div>

                        <div class="text-danger fw-bold">
                            المدفوع: {{ number_format($row['out'],2) }}
                        </div>

                    </div>
                </div>

            @endforeach

        </div>

        <div class="table-responsive">
            <table class="table align-middle">

                <thead class="small text-muted">
                    <tr>
                        <th>التاريخ</th>
                        <th>الطالب</th>
                        <th>الدبلومة</th>
                        <th>الصندوق</th>
                        <th>العملة</th>
                        <th>المبلغ</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($transactions as $t)
                        <tr>
                            <td>{{ $t->trx_date }}</td>
                            <td>{{ optional($t->account->accountable)->full_name ?? '-' }}</td>
                            <td>{{ optional($t->diploma)->name ?? '-' }}</td>
                            <td>{{ $t->cashbox->name ?? '-' }}</td>
                            <td>{{ $t->currency }}</td>
                            <td class="fw-bold">{{ number_format($t->amount,2) }}</td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection