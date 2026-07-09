<?php

namespace App\Traits;

trait HasStudentLabels
{
    public function studentArabicLabels(): array
    {
        return [
            'student_status' => [
                'active'                 => 'مستمر في الدراسة',
                'waiting'                => 'قيد الانتظار',
                'withdrawn'              => 'منسحب',
                'failed'                 => 'راسب',
                'absent_exam'            => 'لم يتقدّم للامتحان',
                'certificate_delivered'  => 'تم تسليم الشهادة',
                'certificate_waiting'    => 'بانتظار الشهادة',
                'registration_ended'     => 'انتهى التسجيل',
                'dismissed'              => 'فُصل الطالب',
                'frozen'                 => 'تم تجميد القيد الدراسي',
            ],
            'registration_status' => [
                'pending'   => 'قيد الانتظار',
                'confirmed' => 'مثبّت',
                'archived'  => 'مؤرشف',
                'dismissed' => 'مرفوض',
                'frozen'    => 'مجمّد',
            ],
            'mode' => [
                'onsite' => 'حضوري',
                'online' => 'أونلاين',
            ],
            'crm_source' => [
                'ad'        => 'إعلان مدفوع',
                'referral'  => 'إحالة / توصية',
                'social'    => 'وسائل التواصل',
                'website'   => 'الموقع الإلكتروني',
                'expo'      => 'معرض / فعالية',
                'other'     => 'أخرى',
            ],
            'crm_stage' => [
                'new'        => 'جديد',
                'follow_up'  => 'متابعة',
                'interested' => 'مهتم',
                'registered' => 'مسجل',
                'rejected'   => 'مرفوض',
                'postponed'  => 'مؤجل',
            ],
        ];
    }
}