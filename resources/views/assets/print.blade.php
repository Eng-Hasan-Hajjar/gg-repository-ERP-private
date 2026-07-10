<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طباعة الأصل</title>
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
        .image-box { border: 1px solid #ddd; padding: 10px; text-align: center; margin: 15px 0; }
        .image-box img { max-width: 100%; max-height: 300px; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; color: white; font-weight: bold; }
        .badge-success { background: #27ae60; }
        .badge-warning { background: #f39c12; }
        .badge-danger { background: #e74c3c; }
        .no-print { text-align: center; margin-bottom: 30px; }
        .print-btn { background: #0d6efd; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; margin: 0 5px; }
        .back-btn { background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .container { box-shadow: none; }
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
            <h2>سجل الأصل</h2>
            <p style="color: #666;">{{ $asset->name }}</p>
        </div>

        <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 15px; text-align: center; border-radius: 5px; margin-bottom: 25px;">
            <p style="color: #856404; font-size: 12px; margin-bottom: 5px;">📌 تاغ الأصل</p>
            <p style="font-size: 22px; font-family: monospace; font-weight: bold; color: #000;">{{ $asset->asset_tag }}</p>
        </div>

        <div class="section">
            <div class="section-title">📋 المعلومات الأساسية</div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">الاسم</div>
                    <div class="info-value"><strong>{{ $asset->name }}</strong></div>
                </div>
                <div class="info-item">
                    <div class="info-label">السيريال</div>
                    <div class="info-value">{{ $asset->serial_number ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">التصنيف</div>
                    <div class="info-value">{{ $asset->category->name ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الفرع</div>
                    <div class="info-value">{{ $asset->branch->name ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الموقع</div>
                    <div class="info-value">{{ $asset->location ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الحالة</div>
                    <div class="info-value">
                        @if($asset->condition == 'excellent')
                            <span class="badge" style="background: #27ae60;">ممتازة</span>
                        @elseif($asset->condition == 'good')
                            <span class="badge" style="background: #3498db;">جيدة</span>
                        @elseif($asset->condition == 'fair')
                            <span class="badge" style="background: #f39c12;">مقبولة</span>
                        @else
                            <span class="badge" style="background: #e74c3c;">سيئة</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">💰 التفاصيل المالية</div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">تاريخ الشراء</div>
                    <div class="info-value">{{ $asset->purchase_date?->format('Y-m-d') ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">التكلفة</div>
                    <div class="info-value">{{ $asset->purchase_cost ?? '-' }} {{ $asset->currency ?? '' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">العدد</div>
                    <div class="info-value"><span class="badge badge-success">{{ $asset->quantity ?? 1 }}</span></div>
                </div>
            </div>
        </div>

        @if($asset->description)
        <div class="section">
            <div class="section-title">📝 الوصف</div>
            <div style="background: #f9f9f9; padding: 15px; border-left: 3px solid #0d6efd; line-height: 1.8;">
                {{ $asset->description }}
            </div>
        </div>
        @endif

        <div class="section">
            <div class="section-title">📸 الصورة</div>
            @if($asset->photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($asset->photo_path))
                <div class="image-box">
                    <img src="{{ asset('storage/app/public/' . $asset->photo_path) }}" alt="{{ $asset->name }}">
                </div>
            @else
                <div style="background: #ecf0f1; padding: 40px; text-align: center; border-radius: 5px; color: #999;">
                    📷 لا توجد صورة
                </div>
            @endif
        </div>

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
