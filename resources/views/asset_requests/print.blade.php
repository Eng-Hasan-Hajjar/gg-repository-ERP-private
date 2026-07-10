<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طباعة الطلب #{{ $assetRequest->id }}</title>
    <style>
        * { margin: 0; padding: 0; }
        body { font-family: Arial; padding: 20px; direction: rtl; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0d6efd; padding-bottom: 20px; }
        .header h2 { color: #333; margin-bottom: 10px; }
        .section { margin-bottom: 25px; }
        .section-title { background: #ecf0f1; padding: 10px 15px; font-weight: bold; margin-bottom: 15px; border-right: 4px solid #0d6efd; }
        .info-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px; }
        .info-item { padding-bottom: 10px; border-bottom: 1px solid #ecf0f1; }
        .info-label { color: #666; font-weight: bold; font-size: 12px; margin-bottom: 5px; }
        .info-value { color: #333; font-size: 15px; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; color: white; font-weight: bold; }
        .no-print { text-align: center; margin-bottom: 30px; }
        .print-btn { background: #0d6efd; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; margin: 0 5px; }
        .back-btn { background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="print-btn" onclick="window.print()">🖨️ طباعة</button>
        <button class="back-btn" onclick="window.history.back()">← عودة</button>
    </div>

    <div class="container">
        <div class="header">
            <h2>سجل الطلب</h2>
            <p style="color: #666;">{{ $assetRequest->title ?? 'طلب لوجستيات' }}</p>
        </div>

        {{-- رقم الطلب --}}
        <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 15px; text-align: center; border-radius: 5px; margin-bottom: 25px;">
            <p style="color: #856404; font-size: 12px; margin-bottom: 5px;">📌 رقم الطلب</p>
            <p style="font-size: 22px; font-family: monospace; font-weight: bold; color: #000;">#{{ $assetRequest->id }}</p>
        </div>

        {{-- المعلومات الأساسية --}}
        <div class="section">
            <div class="section-title">📋 معلومات الطلب</div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">العنوان</div>
                    <div class="info-value"><strong>{{ $assetRequest->title ?? '-' }}</strong></div>
                </div>
                <div class="info-item">
                    <div class="info-label">الحالة</div>
                    <div class="info-value">
                        @php
                            $statusColors = [
                                'pending'     => '#f39c12',
                                'approved'    => '#27ae60',
                                'rejected'    => '#e74c3c',
                                'transferred' => '#3498db',
                            ];
                            $statusLabels = [
                                'pending'     => 'قيد المراجعة',
                                'approved'    => 'مقبول',
                                'rejected'    => 'مرفوض',
                                'transferred' => 'مُرحَّل',
                            ];
                            $status = $assetRequest->status ?? 'pending';
                            $statusColor = $statusColors[$status] ?? '#95a5a6';
                            $statusLabel = $statusLabels[$status] ?? $status;
                        @endphp
                        <span class="badge" style="background: {{ $statusColor }};">{{ $statusLabel }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">النوع</div>
                    <div class="info-value">
                        @if($assetRequest->type === 'purchase')
                            <span class="badge" style="background: #3498db;">شراء</span>
                        @elseif($assetRequest->type === 'repair')
                            <span class="badge" style="background: #f39c12; color: #000;">إصلاح</span>
                        @else
                            {{ $assetRequest->type ?? '-' }}
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">الأولوية</div>
                    <div class="info-value">
                        @if($assetRequest->priority === 'urgent')
                            <span class="badge" style="background: #e74c3c;">🔴 عاجل</span>
                        @elseif($assetRequest->priority === 'normal')
                            <span class="badge" style="background: #3498db;">➖ عادية</span>
                        @else
                            <span class="badge" style="background: #95a5a6;">🔽 منخفضة</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">مقدم الطلب</div>
                    <div class="info-value">{{ $assetRequest->user->name ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الفرع</div>
                    <div class="info-value">{{ $assetRequest->branch->name ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ الطلب</div>
                    <div class="info-value">{{ $assetRequest->created_at?->format('Y-m-d') ?? '-' }}</div>
                </div>
                @if($assetRequest->asset)
                <div class="info-item">
                    <div class="info-label">الأصل المرتبط</div>
                    <div class="info-value">{{ $assetRequest->asset->name ?? '-' }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- الوصف --}}
        @if($assetRequest->description)
        <div class="section">
            <div class="section-title">📝 الوصف</div>
            <div style="background: #f9f9f9; padding: 15px; border-right: 3px solid #0d6efd; line-height: 1.8;">
                {{ $assetRequest->description }}
            </div>
        </div>
        @endif

        {{-- سبب الرفض --}}
        @if($assetRequest->manager_notes)
        <div class="section">
            <div class="section-title">⚠️ ملاحظات المدير</div>
            <div style="background: #ffe6e6; padding: 15px; border-right: 3px solid #e74c3c; line-height: 1.8;">
                {{ $assetRequest->manager_notes }}
            </div>
        </div>
        @endif

        {{-- التذييل --}}
        <div class="footer">
            <p>تم الطباعة: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <script>
        window.addEventListener('afterprint', function() {
            window.history.back();
        });
    </script>
</body>
</html>