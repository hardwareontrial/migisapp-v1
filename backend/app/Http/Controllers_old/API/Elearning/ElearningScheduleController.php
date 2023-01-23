<?php

namespace App\Http\Controllers\API\Elearning;

use App\Http\Controllers\Controller;
use App\Models\API\Elearning\AppElearningQuestion;
use App\Models\API\Elearning\AppElearningQuestionCollection;
use App\Models\API\Elearning\AppElearningSchedule;
use App\Models\API\Elearning\AppElearningUserdataExam;
use App\Models\API\HRIS\AppHrisDepartment;
use App\Models\API\HRIS\AppHrisPosition;
use App\Models\API\SuratJalan\AppSuratJalan;
use App\Models\API\User\AppUser;
use App\Models\API\User\AppUserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class ElearningScheduleController extends Controller
{
  public function listparticipant()
  {
    $listparticipants = [];

    /** All employees */
    $allemp = new stdClass;
    $allemp->value = 'L00';
    $allemp->label = '*ALL KARYAWAN';
    array_push($listparticipants, $allemp);

    /** All Manager  */
    $mgr = new stdClass;
    $mgr->value = 'L34';
    $mgr->label = '*ALL MANAGER';
    array_push($listparticipants, $mgr);

    /** All SPV  */
    $spvpart = new stdClass;
    $spvpart->value = 'L55';
    $spvpart->label = '*ALL SUPERVISOR';
    array_push($listparticipants, $spvpart);

    /** Staff Only */
    $staffpart = new stdClass;
    $staffpart->value = 'L66';
    $staffpart->label = '*ALL STAFF';
    array_push($listparticipants, $staffpart);

    /** Single User List */
    $listemp = AppUser::where('active', 1)
      ->whereHas('position', function($q){
        $q->where('level', '>', 2);
      })
      ->with('position', 'login')
      ->orderBy('name', 'asc')
      ->get();
    foreach($listemp as $i => $ae){
      $emp[$i] = new stdClass;
      // $emp[$i]->value = substr($ae->nik, -3);
      $emp[$i]->value = $ae->nik;
      $emp[$i]->label = $ae->name;
      array_push($listparticipants, $emp[$i]);
    }
    
    /** Single Position List */
    $allposition = AppHrisPosition::whereNotIn('id', [1, 57])->orderBy('name', 'asc')->get();
    foreach($allposition as $k => $pos){
      $posobj[$k] = new stdClass;
      $posobj[$k]->value = 'P-'.$pos->id;
      $posobj[$k]->label = 'POS-'.$pos->name;
      array_push($listparticipants, $posobj[$k]);
    }

    /** Single Department List */
    $alldept = AppHrisDepartment::orderBy('name', 'asc')->get();
    foreach($alldept as $d => $dept){
      $deptobj[$d] = new stdClass;
      $deptobj[$d]->value = 'D-'.$dept->id;
      $deptobj[$d]->label = 'DIV-'.$dept->name;
      array_push($listparticipants, $deptobj[$d]);
    }

    return $listparticipants;
  }

  public function schedulelist()
  {
    $frompage = request()->frompage;

    if($frompage == 'elearningschedule'){
      $condition = request()->category;
      $q = request()->q;
      $data = AppElearningSchedule::with('dataquestion')
        ->where(function ($query) use ($condition, $q){
          $query->whereHas('dataquestion', function($query2) use ($q){
            $query2->where(function($query3) use ($q){
              $query3->where('title', 'like', '%'.$q.'%');
            })
            ->orWhere('note', 'like', '%'.$q.'%');
          })
          ->when($condition == 'upcoming', function($query4){
            $query4->where('startdate_exam', '>', date('Y-m-d H:i:s'));
          })
          ->when($condition == 'now', function($query4){
            $query4->where('startdate_exam', '<=', date('Y-m-d H:i:s'))->where('enddate_exam', '>=', date('Y-m-d H:i:s'));
          })
          ->when($condition == 'done', function($query4){
            $query4->where('enddate_exam', '<', date('Y-m-d H:i:s'));
          });
        })
        ->orderBy(request()->sortby, request()->sortbydesc)
        ->paginate(request()->per_page);
    }elseif($frompage == 'elearningdashboard'){
      $tmps = AppElearningSchedule::with('dataquestion.questions', 'participants_exam')->get();
      $data = array();
      foreach ($tmps as $tmp){
        $count = count($tmp->dataquestion->questions);
        $tmp->dataquestion->qstcount = $count;
        unset($tmp->dataquestion->questions);
        $data[] = $tmp;
      }
    }
    
    return response()->json(['message' => $data], 200);
  }

  public function detailschedule($id)
  {
    $tmp =  AppElearningSchedule::where('id', $id)
      ->with('participants_exam.datauser.position.deptname', 'dataquestion.questions', 'dataquestion.material.materialfile')
      ->first();
    $count = count($tmp->dataquestion->questions);
    $tmp->dataquestion->qstcount = $count;
    unset($tmp->dataquestion->questions);

    foreach ($tmp->participants_exam as $px){
      $shuffled_id = $px->questions_pattern;
      $dataquestions = AppElearningQuestionCollection::whereIn('id', $shuffled_id)->get();
      $reorder = collect([]);

      // $reorder2 = collect([]);
      $reorder2 = [];
      if($px->answers_user){ $cansuser = count($px->answers_user); }else{ $cansuser = 0; }
      if($cansuser > 0){
        foreach($px->answers_user as $au){
          $idau = $au['id'];
          $valau = $au['value'];
          $dq = AppElearningQuestionCollection::find($idau);
          $au['questions_id'] = $dq->questions_id;
          $au['question'] = $dq->question;
          $au['answer_options'] = $dq->answer_options;
          $au['answer_key'] = $dq->answer_key;
          // $reorder2->push($au);
          array_push($reorder2, $au);
        }
        // usort($reorder2, function($a, $b){
        //   if($a['id'] == $b['id']) return (0);
        //   return (($a['id'] < $b['id']) ? -1 : 1);
        // });
        unset($px->answers_user);
        $px->answers_user = $reorder2;
      }else{
        unset($px->answers_user);
        $px->answers_user = [];
      }
      
      /** Get Question */
      foreach ($shuffled_id as $id){
        /** shuffle answer_options */
        $tmpqst = $dataquestions->where('id', $id)->first();
        $tmpao = $tmpqst->answer_options;
        $sfansopt = collect($tmpao)->shuffle();
        unset($tmpqst->answer_options);
        $tmpqst->answer_options = $sfansopt;
        $reorder->push($tmpqst);
      }
      $px->qstpattern = $reorder;
      unset($px->questions_pattern);
    }
    return $tmp;
  }

  public function storeschedule(Request $request)
  {
    $frompage = $request->input('frompage');
    $reqparticipants = json_decode($request->input('participant_id'));
    $filteredparticipants = $this->filterparticipant($reqparticipants);
    $scheduleid = '';
    $soalid = $request->input('soal_id');

    if($frompage == 'elearningscheduleform'){
      $data = AppElearningSchedule::create([
        'question_id' => $soalid,
        'type' => $request->input('type'),
        'startdate_exam' => Carbon::parse($request->input('s_datetime'))->format('Y-m-d H:i:s'),
        'enddate_exam' => Carbon::parse($request->input('e_datetime'))->format('Y-m-d H:i:s'),
        'note' => $request->input('note'),
        'created_by' => auth('sanctum')->user()->detail->id,
        'isactive' => 1,
      ]);
      $scheduleid = $data->id;
      if(!$data){ return response()->json(['message' => 'Data gagal ditambah.'], 500); }
    }else if($frompage == 'elearningscheduledetail'){
      $scheduleid = $request->input('schedule_id');
    }

    $this->setuserdataexams($scheduleid, $filteredparticipants, $soalid);
    return response()->json(['message' => 'Data berhasil ditambah.'], 200);
  }

  public function deleteparticipant($id)
  {
    $data = AppElearningUserdataExam::find($id);
    $data->delete();
    return response()->json(['message' => 'Data berhasil dihapus.'], 200);
  }

  public function updateexamparticipant(Request $request, $id)
  {
    $outputansw = [];
    $inputansw = json_decode($request->input('answersuser'));
    foreach($inputansw as $inansw){
      if($inansw){
        array_push($outputansw, $inansw);
      }
    }

    $data = AppElearningUserdataExam::find($id);
    $data->update([
      'answers_user' => $outputansw,
      'user_start_exam' => $request->input('userstartexam'),
      'user_end_exam' => $request->input('userendexam'),
      'timeleft_seconds' => $request->input('timeleft'),
      'isdone' => $request->input('isdone'),
      'ispassed' => $request->input('ispassed'),
      'score' => $request->input('score'),
      'certificate' => $request->input('certificate'),
    ]);
  }

  private function filterparticipant($requestparticipant)
  {
    /**
     *  Administrator has no position
     *  Director has position level 2
     *  Gen.Manager has position level 3
     *  Manager has position level 4
     *  Supervisor has position level 5
     *  Staff and Others has position level 6
    */

    /** Set Participant */
    $managers = AppUser::has('position')->whereHas('position', function($q){
        $q->where('level', 4);
      })
      ->with('login')
      ->get()
      ->pluck('nik');

    $supervisor = AppUser::has('position')->whereHas('position', function($q){
        $q->where('level', 5);
      })
      ->with('login')
      ->get()
      ->pluck('nik');

    $staffs = AppUser::has('position')->whereHas('position', function($q){
        $q->where('level', 6);
      })
      ->with('login')
      ->get()
      ->pluck('nik');
    

    $rawparticipants = array();
    $reqparticipants = $requestparticipant;

    foreach($reqparticipants as $reqp){
      if($reqp == 'L00'){
        $allusers = AppUser::has('position')
          ->whereNotIn('position_id', [57])
          ->where('active', 1)
          ->get();
        foreach($allusers as $au){
          // $rawparticipants[] = substr($au->nik, -3);
          $rawparticipants[] = $au->nik;
        }
      }else if($reqp == 'L34'){
        foreach($managers as $mgr){
          $rawparticipants[] = $mgr;
        }
      }else if($reqp == 'L55'){
        foreach($supervisor as $pv){
          $rawparticipants[] = $pv;
        }
      }else if($reqp == 'L66'){
        foreach($staffs as $st){
          $rawparticipants[] = $st;
        }
      }else if(substr($reqp, 0, 2) == 'P-'){
        $posusers = AppUser::where('position_id', '=', explode('P-', $reqp)[1])
          ->where('active', 1)
          ->get();
        foreach($posusers as $pu){
          $rawparticipants[] = substr($pu->nik, -3);
        }
      }else if(substr($reqp, 0, 2) == 'D-'){
        $explode = explode('D-', $reqp)[1];
        $deptt = AppUser::has('position')
        ->whereHas('position', function($q) use ($explode){
          $q->where('dept_id', $explode);
        })
        ->with('position')
        ->get();
        foreach($deptt as $dpt){
          // $rawparticipants[] = substr($dpt->nik, -3);
          $rawparticipants[] = $dpt->nik;
        }
      }else{
        $rawparticipants[] = $reqp;
      }
    }
    $selected_participants = array_unique($rawparticipants, SORT_REGULAR);
    return $selected_participants;
  }

  private function setuserdataexams($schedule_id, $users_nik, $questionid)
  {
    foreach ($users_nik as $usernik){
      $shuffled_id = $this->shufflequestion($questionid);
      AppElearningUserdataExam::create([
        'schedule_id' => $schedule_id,
        'user_nik' => $usernik,
        'questions_pattern' => $shuffled_id,
        'isdone' => 2,
        'ispassed' => 2,
      ]);
    }
  }

  private function shufflequestion($questionid)
  {
    $questions = AppElearningQuestionCollection::where('questions_id', $questionid)->get()->pluck('id');
    $shuffled = $questions->shuffle();
    return $shuffled;
  }

  public function userexamdetail($nik)
  {
    return AppUser::registeredkaryawan();
  }

  public function testfunction()
  {
    /** shuffled question [WORKED] */
    $questions = AppElearningQuestionCollection::where('questions_id', 1)->get()->pluck('id');
    $d = $questions->shuffle();
    return $d;

    /** return question sortBy array shuffled id [WORKED]*/ 
    // $shuffled_id = [3,1];
    // $dataquestions = AppElearningQuestionCollection::whereIn('id', $shuffled_id)->get();
    // $reorder = collect([]);
    // foreach ($shuffled_id as $id){
    //   $reorder->push($dataquestions->where('id', $id)->first());
    // }
    // return $reorder;
  }

  public function testfunction2()
  {
    return AppSuratJalan::with('detail', 'creator')->get();
  }
}
