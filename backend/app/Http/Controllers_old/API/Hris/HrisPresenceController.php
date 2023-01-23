<?php

namespace App\Http\Controllers\API\Hris;

use App\Http\Controllers\Controller;
use App\Models\API\HRIS\AppHrisKaryawanSource;
use App\Models\API\HRIS\AppHrisPresence;
use App\Models\API\HRIS\AppHrisPresenceExportLog;
use App\Models\API\HRIS\AppHrisPresenceSource;
use App\Models\API\User\AppUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HrisPresenceController extends Controller
{
  public function handleData()
  {
    return AppHrisPresence::orderBy('id', 'desc')->get();
  }

  public function handleSync()
  {
    $input_start = request()->startdate;
    $input_end = request()->enddate;
    $check = AppHrisPresence::count();

    if($check == 0){
      $start = $input_start;
      $end = $input_end;
    }
    else{
      $lastdata = AppHrisPresence::select('id', 'scan_date')->orderBy('scan_date', 'desc')->first();
      $source_lastdata = AppHrisPresenceSource::where('scan_date', '>', $lastdata->scan_date)->min('scan_date');
      $start = $source_lastdata;
      $end = $input_end;
    }

    $data = AppHrisPresenceSource::tanpaHarianLepas()
        ->whereBetween('scan_date', [$start, $end])
        ->with('name')
        ->get();

    foreach($data as $d){
      $pin = $d->pin;
      $scandate = date('Y-m-d H:i:s', strtotime($d->scan_date));
      $pin_name = $d->name ? $d->name['name'] : 'NULL';
      
      $store = AppHrisPresence::create([
        'pin' => $pin,
        'name' => $pin_name,
        'scan_date' => $scandate,
      ]);
    }

    if(!$store){ return response()->json(['message' => 'Data gagal disimpan.'], 500); }
    return response()->json(['message' => 'Data berhasil disimpan.'], 200);
  }

  public function handleAutoSync()
  {
    $check = AppHrisPresence::count();

    if($check == 0){
      $start = Carbon::now()->format('Y-m-d 00:00:00');
      $end = Carbon::now()->format('Y-m-d H:i:s');
      // $start = '2021-12-03 00:00:00';
      // $end = '2021-12-03 23:59:59';
    }
    else{
      $lastdata = AppHrisPresence::select('id', 'scan_date')->orderBy('scan_date', 'desc')->first();
      $source_lastdata = AppHrisPresenceSource::where('scan_date', '>', $lastdata->scan_date)->min('scan_date');
      $start = $source_lastdata;
      $end = Carbon::now()->format('Y-m-d H:i:s');
      // $end = '2021-12-03 23:59:59';
    }

    $data = AppHrisPresenceSource::tanpaHarianLepas()
        ->whereBetween('scan_date', [$start, $end])
        ->with('name')
        ->get();
    
    if($data->count() > 0 ){
      foreach($data as $d){
        $pin = $d->pin;
        $scandate = date('Y-m-d H:i:s', strtotime($d->scan_date));
        $pin_name = $d->name ? $d->name['name'] : 'NULL';
        
        $store = AppHrisPresence::create([
          'pin' => $pin,
          'name' => $pin_name,
          'scan_date' => $scandate,
        ]);
      }

      /** Generate .txt File */
      // storage_path = storage_path('app/public/app_hris/presence_txt/')

      if(!$store){ return response()->json(['message' => 'Data gagal disimpan.'], 500); }
      return response()->json(['message' => 'Data berhasil disimpan.'], 200);
    }
  }

  public function handleAutoGenerateTxt()
  {
    $checkpresence = AppHrisPresence::count();
    $checkexport = AppHrisPresenceExportLog::count();

    if($checkpresence == 0){
      AppHrisPresenceExportLog::create([
        'startdate' => NULL,
        'enddate' => NULL,
        'note' => 'No Presence Available To Export.'
      ]);
    }else{
      if($checkexport == 0){
        $inputstart = AppHrisPresence::select('id', 'scan_date')->orderBy('scan_date', 'asc')->first();
        $inputend = AppHrisPresence::select('id', 'scan_date')->orderBy('scan_date', 'desc')->first();
        $data = AppHrisPresence::whereBetween('scan_date', [$inputstart->scan_date, $inputend->scan_date])->get();
        $filename = str_replace(['-', ' ', ':'], ['', '',''], $inputstart->scan_date).'-'.str_replace(['-', ' ', ':'], ['', '',''], $inputend->scan_date).'.txt';
        $content = "";
        foreach ($data as $d){
          $content .= $d->pin;
          $content .= ',';
          $content .= $d->name;
          $content .= ',';
          $content .= date('d/m/Y H:i', strtotime($d->scan_date));
          $content .= "\n";
        }
        Storage::disk('absensiexport')->put($filename, $content);
        AppHrisPresenceExportLog::create([
          'startdate' => $inputstart->scan_date,
          'enddate' => $inputend->scan_date,
          'note' => 'Export created with filename '.$filename,
        ]);
      }else{
        $lastexportdata = AppHrisPresenceExportLog::orderBy('id', 'desc')->first();
        if(empty($lastexportdata->enddate)){
          $inputstart = AppHrisPresence::select('id', 'scan_date')->orderBy('scan_date', 'asc')->first();
          $inputend = AppHrisPresence::select('id', 'scan_date')->orderBy('scan_date', 'desc')->first();
          $data = AppHrisPresence::whereBetween('scan_date', [$inputstart->scan_date, $inputend->scan_date])->get();
          $filename = str_replace(['-', ' ', ':'], ['', '',''], $inputstart->scan_date).'-'.str_replace(['-', ' ', ':'], ['', '',''], $inputend->scan_date).'.txt';
          $content = "";
          foreach ($data as $d){
            $content .= $d->pin;
            $content .= ',';
            $content .= $d->name;
            $content .= ',';
            $content .= date('d/m/Y H:i', strtotime($d->scan_date));
            $content .= "\n";
          }
          Storage::disk('absensiexport')->put($filename, $content);
          AppHrisPresenceExportLog::create([
            'startdate' => $inputstart->scan_date,
            'enddate' => $inputend->scan_date,
            'note' => 'Export created with filename '.$filename,
          ]);
        }else{
          $inputstart = $lastexportdata->enddate;
          $inputend = AppHrisPresence::select('id', 'scan_date')->orderBy('scan_date', 'desc')->first();
          $data = AppHrisPresence::whereBetween('scan_date', [$inputstart, $inputend->scan_date])->get();
          $filename = str_replace(['-', ' ', ':'], ['', '',''], $inputstart).'-'.str_replace(['-', ' ', ':'], ['', '',''], $inputend->scan_date).'.txt';
          $content = "";
          foreach ($data as $d){
            $content .= $d->pin;
            $content .= ',';
            $content .= $d->name;
            $content .= ',';
            $content .= date('d/m/Y H:i', strtotime($d->scan_date));
            $content .= "\n";
          }
          Storage::disk('absensiexport')->put($filename, $content);
          AppHrisPresenceExportLog::create([
            'startdate' => $inputstart,
            'enddate' => $inputend->scan_date,
            'note' => 'Export created with filename '.$filename,
          ]);
        }
      }
    }
  }

  public function handleGenerateData()
  {
    $input_start = request()->startdate;
    $input_end = request()->enddate;
    return AppHrisPresence::whereBetween('scan_date', [$input_start, $input_end])->orderBy('id', 'asc')->get();
  }

  public function scanNewPegawai()
  {
    $lastpin = AppUser::registeredkaryawan()->get()->pluck('nik')->last();
    $lastpinsource = AppHrisKaryawanSource::pluck('pegawai_pin')->last();
    
    if($lastpin !== $lastpinsource){
      $nextpin = AppHrisKaryawanSource::where('pegawai_pin', '>', $lastpin)->min('pegawai_pin');
      $getdata = AppHrisKaryawanSource::whereBetween('pegawai_pin', [$nextpin, $lastpinsource])->get();

      if($getdata->count() > 0){
        foreach ($getdata as $newuser){
          $storenewuser = AppUser::create([
            'nik' => $newuser->pegawai_pin,
            'name' => $newuser->pegawai_nama,
            'active' => 1,
          ]);
        }
        if(!$storenewuser){ return response()->json(['message' => 'Data gagal disimpan']); }
        return response()->json(['message' => 'Data berhasil disimpan.']);
      }
    }
  }

  public function testzkConnection()
  {
    //
  }
}
