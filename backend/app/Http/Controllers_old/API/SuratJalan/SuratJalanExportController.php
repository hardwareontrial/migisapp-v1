<?php

namespace App\Http\Controllers\API\SuratJalan;

use App\Exports\API\SuratJalan\ByDefault;
use App\Http\Controllers\Controller;
use App\Models\API\GaInventaris\AppGainventaris;
use App\Models\API\HRIS\AppHrisPosition;
use App\Models\API\User\AppUser;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class SuratJalanExportController extends Controller
{
  public function handleListCreatorExport()
  {
    return AppUser::registeredkaryawan()->get();
  }

  public function handleRequestExport()
  {
    $creatorid = json_decode(request('creator'));
    $sentstartdate = request('sentstartdate');
    $sentenddate = request('sentenddate');
    $createstartdate = request('createstartdate');
    $createenddate = request('createenddate');
    $filename = 'sjexport_'. Carbon::now()->format('ymd_hi').'.xlsx';
    
    return Excel::download(new ByDefault($creatorid, $createstartdate, $createenddate, $sentstartdate, $sentenddate), $filename);
  }

  public function testposition()
  {
    // $user = AppUser::find(103);
    // $pos = AppHrisPosition::find(37);
    // $pos->user2()->sync($user);
    return AppHrisPosition::where('id', 37)
      ->with('user2')
      ->get();
  }

  public function testinventarisassignuser()
  {
    $user = AppUser::find(97);
    $inv = AppGainventaris::find([]);
    $user->inventaris()->sync($inv);
  }
}
