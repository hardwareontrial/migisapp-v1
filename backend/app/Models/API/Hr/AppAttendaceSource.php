<?php

namespace App\Models\API\Hr;

use App\Models\API\User\AppUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppAttendaceSource extends Model
{
  use HasFactory;
  protected $connection = 'mysql_absensi';
  protected $table = 'att_log';

  public static function tanpaHarianLepas()
  {
    // return AppAttendaceSource::whereNotIn('pin', ['002', '<=', '030']);
    $hl = ['001', '002', '003', '004', '005', '006', '007', '008', '009', '010', 
    '011', '012', '013', '014', '015', '016', '017', '018', '019', '020'];
    return AppAttendaceSource::whereNotIn('pin', $hl);
  }

  public function name()
  {
    return $this->setConnection('mysql')->hasOne(AppUser::class, 'nik', 'pin');
  }
}
