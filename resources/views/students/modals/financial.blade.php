@if($paymentPlans->isEmpty())
    <div class="alert alert-info">لا توجد خطط دفع مسجلة لهذا الطالب.</div>
@else
    @foreach($paymentPlans as $plan)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">{{ $plan->diploma->name ?? 'دبلومة' }}</h6>
                <span class="badge {{ $plan->remaining > 0 ? 'bg-danger' : 'bg-success' }}">
                    {{ $plan->remaining > 0 ? 'عليه ذمة' : 'مسدّد' }}
                </span>
            </div>
            <div class="row g-2 text-center">
                <div class="col-4">
                    <div class="fw-bold text-primary">{{ number_format($plan->total_amount, 2) }}</div>
                    <div class="text-muted small">الإجمالي</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold text-success">{{ number_format($plan->paid, 2) }}</div>
                    <div class="text-muted small">المدفوع</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold {{ $plan->remaining > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($plan->remaining, 2) }}
                    </div>
                    <div class="text-muted small">المتبقي</div>
                </div>
            </div>

            @if($plan->installments->count())
            <hr class="my-2">
            <div class="small">
                <div class="fw-bold mb-1">الأقساط:</div>
                @foreach($plan->installments as $inst)
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span>{{ $inst->due_date }}</span>
                    <span>{{ number_format($inst->amount, 2) }} {{ $plan->currency }}</span>
                    <span class="badge {{ $inst->paid_at ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $inst->paid_at ? 'مدفوع' : 'معلّق' }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endforeach
@endif