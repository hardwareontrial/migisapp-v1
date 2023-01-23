<?php

namespace App\Models\API\Elearning;

use App\Models\API\User\AppUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppElearningSchedule extends Model
{
  use HasFactory;
  protected $table = "app_elearning_schedules";
  protected $fillable = [
    'title',
    'question_id',
    'type',
    'startdate_exam',
    'enddate_exam',
    'note',
    'participant_id',
    'questions_count',
    'created_by',
    'isactive',
  ];
  protected $casts = [
    'participant_id' => 'array',
  ];

  public function dataquestion()
  {
    // return $this->hasOne(AppElearningQuestion::class, 'id', 'question_id');
    return $this->hasOne(AppElearningQuestion::class, 'id', 'question_id');
  }

  public function participants_exam()
  {
    // return $this->hasMany(AppElearningUserdataExam::class, 'schedule_id', 'id');
    return $this->hasMany(AppElearningUserdataExam::class, 'schedule_id', 'id');
  }
}
