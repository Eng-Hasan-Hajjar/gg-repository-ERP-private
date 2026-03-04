<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramManagement extends Model
{
    protected $table = 'program_managements';
    
    protected $fillable = [
        'diploma_id',
        'manager_id',

            'trainer_id',
        'communication_manager',


        'market_study',
        'trainer_assigned',
        'contracts_ready',
        'materials_ready',
        'sessions_uploaded',
        'certificate_source',
        'details_file',
        'price',
        'media_form_sent',
        'direct_ads',
        'content_ready',
        'opening_invitation',
        'opening_snippets',
        'carousel',
        'designs',
        'stories',
        'campaign_start',
        'campaign_end',
        'campaign_budget',
        'confirmed_students',
        'duration_months',
        'hours',
        'start_date',
        'end_date',
        'mid_exam',
        'final_exam',
        'projects',
        'attendance_certificate',
        'university_certificate',
        'cards_ready',
        'admin_session_1',
        'admin_session_2',
        'admin_session_3',
        'evaluations_done',
        'graduates_count',
        'notes'
    ];

    protected $casts = [
        'market_study' => 'boolean',
        'trainer_assigned' => 'boolean',
        'contracts_ready' => 'boolean',
        'materials_ready' => 'boolean',
        'sessions_uploaded' => 'boolean',
        'media_form_sent' => 'boolean',
        'direct_ads' => 'boolean',
        'content_ready' => 'boolean',
        'opening_invitation' => 'boolean',
        'opening_snippets' => 'boolean',
        'carousel' => 'boolean',
        'designs' => 'boolean',
        'stories' => 'boolean',
        'projects' => 'boolean',
        'attendance_certificate' => 'boolean',
        'university_certificate' => 'boolean',
        'cards_ready' => 'boolean',
        'admin_session_1' => 'boolean',
        'admin_session_2' => 'boolean',
        'admin_session_3' => 'boolean',
        'evaluations_done' => 'boolean',
    ];

    public function diploma()
    {
        return $this->belongsTo(Diploma::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class,'manager_id');
    }

  

    public function trainer()
    {
        return $this->belongsTo(Employee::class,'trainer_id');
    }

    
}