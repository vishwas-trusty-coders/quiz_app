<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetOurTeamPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 
        'section1_title', 'leaders1_image', 'exam_score_title', 'exam_score_info', 'leaders1_name', 'leaders1_designation', 'leaders1_description', 'credential_title', 'credential_info',
        'leaders2_image', 'leaders2_name', 'leaders2_designation', 'leaders2_description',
        'section2_title', 'section2_desc', 'tutor_detail',
    ];

    protected $casts = [
        'exam_score_info' => 'array',
        'credential_info' => 'array',
        'tutor_detail' => 'array'
    ];
}
