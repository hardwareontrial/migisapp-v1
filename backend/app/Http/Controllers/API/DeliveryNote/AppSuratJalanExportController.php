<?php

namespace App\Http\Controllers\API\DeliveryNote;

use App\Exports\API\SuratJalan\ByDefault;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AppSuratJalanExportController extends Controller
{
  public function export()
  {
    $creatorid = json_decode(request('creator'));
    $sentstartdate = request('sentstartdate');
    $sentenddate = request('sentenddate');
    $createstartdate = request('createstartdate');
    $createenddate = request('createenddate');
    $filename = 'sjexport_'. Carbon::now()->format('ymd_hi').'.xlsx';
    
    return Excel::download(new ByDefault($creatorid, $createstartdate, $createenddate, $sentstartdate, $sentenddate), $filename);
  }

  public function secondmethod()
  {
    $url = 'https://bit.ly/';
    $urlpath = 'induksi-tamu';
    $qrname = 'barcode-'.$urlpath.'(2).png';
    // QrCode::format('png')
    //   ->gradient(0, 142, 166, 164, 57, 35, 'diagonal')
    //   ->style('round')
    //   ->size(500)
    //   ->merge('/public/image/mig.png', .28)
    //   ->generate($url.$urlpath, storage_path('app/public/app_inventaris/qrcode/'.$qrname));
    QrCode::format('png')
      ->backgroundColor(255, 156, 0)
      // ->color(244, 231, 11)
      // ->gradient(244, 231, 11, 164, 57, 35, 'diagonal')  
      ->style('round')
      ->size(500)
      ->merge('/public/image/mig.png', .28)
      ->generate($url.$urlpath, storage_path('app/public/app_inventaris/qrcode/'.$qrname));
  }
}
