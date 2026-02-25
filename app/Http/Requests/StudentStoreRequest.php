<?php
// app/Http/Requests/StudentStoreRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'first_name' => 'required|string|max:120',
      'last_name' => 'nullable|string|max:120',
      'full_name' => 'required|string|max:190',
      'phone' => 'required|string|max:50',
      'whatsapp' => 'required|string|max:120',


      'branch_id' => 'required|exists:branches,id',
      'mode' => 'required|in:onsite,online',
      'status' => 'required|string',

      'diploma_ids' => 'required|array',
      'diploma_ids.*' => 'exists:diplomas,id',

      // nested arrays
      'crm' => 'required|array',
     
     
      
      'crm.organization' => 'required|string|max:190',
      'crm.source' => 'required|in:ad,referral,social,website,expo,other',
      'crm.need' => 'required|string',
      'crm.stage' => 'required|in:new,follow_up,interested,registered,rejected,postponed',
      'crm.notes' => 'required|string',

      'crm.country' => 'required|string|max:100',
      'crm.province' => 'required|string|max:100',
      'crm.study' => 'required|string|max:150',
      'crm.job' => 'required|string|max:150',



      'profile' => 'required|array',
      'profile.arabic_full_name' => 'required|string|max:190',
      'profile.nationality' => 'required|string|max:120',
      'profile.birth_date' => 'required|date',
      'profile.national_id' => 'required|string|max:120',
     // 'profile.address' => 'required|string|max:255',
      'profile.exam_score' => 'required|numeric|min:0|max:999.99',
      'profile.notes' => 'required|string',


      'profile.photo' => 'nullable|image|max:2048',
      'profile.info_file' => 'nullable|file|max:5120',
      'profile.identity_file' => 'nullable|file|max:5120',
      'profile.attendance_certificate' => 'nullable|file|max:5120',
      'profile.certificate_pdf' => 'nullable|mimes:pdf|max:5120',
      'profile.certificate_card' => 'nullable|file|max:5120',


      'profile.level' => 'required|string|max:100',
      'profile.stage_in_state' => 'required|string|max:120',

      'profile.education_level' => 'required|string|max:120',
      'profile.message_to_send' => 'required|string',

    ];
  }

public function messages(): array
{
    return [

        /*
        |--------------------------------------------------------------------------
        | بيانات الطالب الأساسية
        |--------------------------------------------------------------------------
        */

        'first_name.required' => 'يجب إدخال الاسم',
        'first_name.string'   => 'الاسم يجب أن يكون نصاً',
        'first_name.max'      => 'الاسم طويل جداً',

        'last_name.string' => 'الكنية يجب أن تكون نصاً',
        'last_name.max'    => 'الكنية طويلة جداً',

        'full_name.required' => 'يجب إدخال الاسم الكامل',
        'full_name.string'   => 'الاسم الكامل يجب أن يكون نصاً',
        'full_name.max'      => 'الاسم الكامل طويل جداً',

        'phone.required' => 'يجب إدخال رقم الهاتف',
        'phone.max'      => 'رقم الهاتف طويل جداً',

        'whatsapp.required' => 'يجب إدخال رقم الواتساب',

        'branch_id.required' => 'يجب اختيار الفرع',
        'branch_id.exists'   => 'الفرع المحدد غير موجود',

        'mode.required' => 'يجب اختيار نوع الطالب',
        'mode.in'       => 'قيمة نوع الطالب غير صحيحة',

        'status.required' => 'يجب تحديد حالة الطالب',

        /*
        |--------------------------------------------------------------------------
        | الدبلومات
        |--------------------------------------------------------------------------
        */

        'diploma_ids.required'   => 'يجب اختيار دبلومة واحدة على الأقل',
        'diploma_ids.array'      => 'اختيار الدبلومات غير صحيح',
        'diploma_ids.*.exists'   => 'إحدى الدبلومات المختارة غير موجودة',

        /*
        |--------------------------------------------------------------------------
        | بيانات CRM
        |--------------------------------------------------------------------------
        */

        'crm.required' => 'يجب إدخال بيانات CRM',


       

        'crm.organization.required' => 'يجب إدخال جهة العمل / المؤسسة',

        'crm.source.required' => 'يجب تحديد مصدر العميل',
        'crm.source.in'       => 'قيمة المصدر غير صحيحة',

        'crm.need.required' => 'يجب كتابة احتياج الطالب',

        'crm.stage.required' => 'يجب تحديد المرحلة',
        'crm.stage.in'       => 'قيمة المرحلة غير صحيحة',

        'crm.notes.required' => 'يجب إدخال ملاحظات CRM',

        'crm.country.required'  => 'يجب إدخال البلد',
        'crm.province.required' => 'يجب إدخال المحافظة',
        'crm.study.required'    => 'يجب إدخال مجال الدراسة',
        'crm.job.required'      => 'يجب إدخال العمل',

        /*
        |--------------------------------------------------------------------------
        | الملف التفصيلي Profile
        |--------------------------------------------------------------------------
        */

        'profile.required' => 'يجب إدخال بيانات الملف التفصيلي',

        'profile.arabic_full_name.required' => 'يجب إدخال الاسم باللاتيني',

        'profile.nationality.required' => 'يجب إدخال الجنسية',

        'profile.birth_date.required' => 'يجب إدخال تاريخ التولد',
        'profile.birth_date.date'     => 'تاريخ التولد غير صالح',

        'profile.national_id.required' => 'يجب إدخال الرقم الوطني',

      //  'profile.address.required' => 'يجب إدخال العنوان',

        'profile.exam_score.required' => 'يجب إدخال العلامة الامتحانية',
        'profile.exam_score.numeric'  => 'العلامة يجب أن تكون رقم',
        'profile.exam_score.min'      => 'العلامة غير صحيحة',

        'profile.notes.required' => 'يجب إدخال الملاحظات',

        'profile.level.required' => 'يجب إدخال مستوى اللغة',

        'profile.stage_in_state.required' => 'يجب إدخال المرحلة / الستاج',

        'profile.education_level.required' => 'يجب إدخال المستوى التعليمي',

        'profile.message_to_send.required' => 'يجب إدخال الرسالة المرسلة للطالب',

        /*
        |--------------------------------------------------------------------------
        | الملفات المرفوعة
        |--------------------------------------------------------------------------
        */

        'profile.photo.required' => 'يجب رفع صورة الطالب',
        'profile.photo.image'    => 'الملف يجب أن يكون صورة',
        'profile.photo.max'      => 'حجم الصورة كبير جداً',

        'profile.info_file.required' => 'يجب رفع ملف المعلومات',

        'profile.identity_file.required' => 'يجب رفع ملف الهوية',

        'profile.attendance_certificate.required' => 'يجب رفع شهادة الحضور',

        'profile.certificate_pdf.required' => 'يجب رفع الشهادة PDF',
        'profile.certificate_pdf.mimes'    => 'يجب أن يكون الملف بصيغة PDF',

        'profile.certificate_card.required' => 'يجب رفع شهادة الكرتون',

    ];
}




public function attributes(): array
{
    return [
        'first_name' => 'الاسم',
        'last_name' => 'الكنية',
        'full_name' => 'الاسم الكامل',
        'phone' => 'رقم الهاتف',
        'whatsapp' => 'رقم الواتساب',
        'branch_id' => 'الفرع',
        'mode' => 'نوع الطالب',
        'status' => 'حالة الطالب',
        'diploma_ids' => 'الدبلومات',
        
        'crm.country' => 'البلد',
        'crm.province' => 'المحافظة',
        'crm.study' => 'الدراسة',
        'crm.job' => 'العمل',
        'profile.birth_date' => 'تاريخ التولد',
        'profile.exam_score' => 'العلامة الامتحانية',
    ];
}




}
