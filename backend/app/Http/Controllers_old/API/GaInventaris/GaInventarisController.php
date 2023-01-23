<?php

namespace App\Http\Controllers\API\GaInventaris;

use App\Http\Controllers\Controller;
use App\Models\API\GaInventaris\AppGainventaris;
use App\Models\API\GaInventaris\AppGainventarisLog;
use App\Models\API\GaInventaris\AppGainventarisSell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GaInventarisController extends Controller
{
  public function handleSourceData()
  {
    $cari = request('q');
    $authuserdeptid = request('authuserdeptid');
    $authuseradmin = request('authuseradmin');
    $data = AppGainventaris::where(function ($query) use ($cari, $authuseradmin, $authuserdeptid){ $query
      ->where(function ($query) use ($authuseradmin, $authuserdeptid) {
        if(!$authuseradmin || $authuserdeptid == 8){ $query->whereIn('pemilik_id', [1, 2]); }
        else if(!$authuseradmin || $authuserdeptid == 6){ $query->whereIn('pemilik_id', [3]); }
        else if($authuseradmin){ $query->whereNotNull('pemilik_id'); }
      })
      ->where(function ($query) use ($cari) { $query
        ->whereHas('merkname', function($query) use ($cari){
          $query->where('name', 'LIKE', '%'.$cari.'%');
        })
        ->orWhereHas('locname', function($query) use ($cari){
          $query->where('name', 'LIKE', '%'.$cari.'%');
        })
        ->orWhereHas('user1name', function($query) use ($cari){ $query
          ->where('name', 'LIKE', '%'.$cari.'%')
          ->orWhereHas('position', function($query) use ($cari){ $query
            ->where('name', 'LIKE', '%'.$cari.'%'); 
          });
        })
        ->orWhereHas('user2name', function($query) use ($cari){ $query
          ->where('name', 'LIKE', '%'.$cari.'%')
          ->orWhereHas('position', function($query) use ($cari){ $query
            ->where('name', 'LIKE', '%'.$cari.'%'); 
          });
        })
        ->orWhere('kd_brg', 'LIKE', '%'.$cari.'%')
        ->orWhere('nama_brg', 'LIKE', '%'.$cari.'%');
      });
    })
    ->orderBy(request()->sortby, request()->sortbydesc)
    ->with('locname', 'merkname', 'user1name', 'user2name', 'user1name.position.deptname', 'user2name.position.deptname')
    ->paginate(request()->per_page);
    return response()->json(['message' => $data], 200);
  }

  public function handleCode()
  {
    $base = 'NB ';
    if(AppGainventaris::where('kd_brg', 'LIKE', $base.'%')->count() > 0){
        $last = AppGainventaris::latest('id')->first();
        $count = substr($last->kd_brg, 2) +1;
        $kode = $base.str_pad($count, 4, '0', STR_PAD_LEFT);
    }else{
        $kode = $base.'0001';
    }
    return $kode;
  }

  public function handleStore(Request $request)
  {

    $ipf = env('IP_FRONTEND');
    if(!$ipf) return response()->json(['message' => 'Error (ENV_IP_FRONTEND) tidak dapat memproses.'], 500);
    $tablename = app(AppGainventaris::class)->getTable();

    $this->validate($request, [
      'nama_brg' => 'required',
      'tglbeli' => 'required',
      'status' => 'required',
      'merk' => 'required',
      'lokasi' => 'required',
      'deptbeli' => 'required',
    ],
    [
      'nama_brg.required' => 'Nama barang tidak boleh kosong.',
      'tglbeli.required' => 'Tanggal beli tidak boleh kosong.',
      'status.required' => 'Pilih salah satu Status.',
      'merk.required' => 'Pilih salah satu Merk.',
      'lokasi.required' => 'Pilih salah satu Lokasi.',
      'deptbeli.required' => 'Pilih salah satu Dept.Pemilik.',
    ]);

    $db = DB::select("SHOW TABLE STATUS LIKE '".$tablename."'");
    $id = $db[0]->Auto_increment;

    $url = 'http://'.$ipf.'/inventaris/show/'.$id;

    $qrname = str_replace(' ', '', $request->input('kode_brg')).'.png';
    QrCode::size(300)
      ->format('png')
      ->style('round')
      ->merge('/public/image/mig.png', .25)
      ->generate($url, storage_path('app/public/app_inventaris/qrcode/'.$qrname));

    if ($request->input('status') == 1 || $request->input('status') == 3) {
      $store = AppGainventaris::create([
        'kd_brg' => $request->input('kode_brg'),
        'nama_brg' => strtoupper($request->input('nama_brg')),
        'tgl_beli' => $request->input('tglbeli'),
        'harga' => $request->input('harga'),
        'toko' => strtoupper($request->input('toko')),
        'spesifikasi' => strtoupper($request->input('spesifikasi')),
        'merk_id' => $request->input('merk'),
        'lokasi_id' => $request->input('lokasi'),
        'status_id' => $request->input('status'),
        'pemilik_id' => $request->input('deptbeli'),
        'serialnumber' => strtoupper($request->input('sn')),
        'active' => true,
        'qrcode' => $qrname,
      ]);

    }else
    if ($request->input('status') == 2) {
      $this->validate($request, [ 'user1' => 'required' ],[ 'user1.required' => 'Pilih salah satu User.' ]);

      $user1 = $request->input('user1');
      $user2 = $request->input('user2');
      // $dept = '1';

      $store = AppGainventaris::create([
        'kd_brg' => $request->input('kode_brg'),
        'nama_brg' => strtoupper($request->input('nama_brg')),
        'tgl_beli' => $request->input('tglbeli'),
        'harga' => $request->input('harga'),
        'toko' => strtoupper($request->input('toko')),
        'spesifikasi' => strtoupper($request->input('spesifikasi')),
        'merk_id' => $request->input('merk'),
        'lokasi_id' => $request->input('lokasi'),
        'status_id' => $request->input('status'),
        'pemilik_id' => $request->input('deptbeli'),
        'serialnumber' => strtoupper($request->input('sn')),
        'active' => true,
        'qrcode' => $qrname,
        'user1_id' => $user1,
        'user2_id' => $user2,
        // 'dept_user' => $dept,
      ]);

    }
    if(!$store) { return response()->json(['message' => 'Data gagal disimpan.'], 500); }
    return response()->json(['message' => 'Data berhasil disimpan.'], 200);
  }

  public function handleUpdate(Request $request, $id)
  {
    $this->validate($request, [
      'nama_brg' => 'required',
      'tglbeli' => 'required',
      'status' => 'required',
      'merk' => 'required',
      'lokasi' => 'required',
    ],
    [
      'nama_brg.required' => 'Nama barang tidak boleh kosong.',
      'tglbeli.required' => 'Tanggal beli tidak boleh kosong.',
      'status.required' => 'Pilih salah satu status.',
      'merk.required' => 'Pilih salah satu Merk.',
      'lokasi.required' => 'Pilih salah satu lokasi.',
    ]);

    $code = str_replace('-', ' ', $id);
    $update = AppGainventaris::where('kd_brg', $code)->first();

    if ($request->input('status') == 1 || $request->input('status') == 3) {
      $update->update([
        'nama_brg' => strtoupper($request->input('nama_brg')),
        'tgl_beli' => $request->input('tglbeli'),
        'harga' => $request->input('harga'),
        'toko' => strtoupper($request->input('toko')),
        'spesifikasi' => strtoupper($request->input('spesifikasi')),
        'merk_id' => $request->input('merk'),
        'lokasi_id' => $request->input('lokasi'),
        'status_id' => $request->input('status'),
        'pemilik_id' => $request->input('deptbeli'),
        'serialnumber' => strtoupper($request->input('sn')),
        'user1_id' => null,
        'user2_id' => null,
        'dept_user' => null,
      ]);
    }else
    if ($request->input('status') == 2) {
      $this->validate($request, [ 'user1' => 'required' ],[ 'user1.required' => 'Pilih salah satu User.' ]);
      $user1 = $request->input('user1');
      $user2 = $request->input('user2');
      // $dept = '1';

      $update->update([
        'nama_brg' => strtoupper($request->input('nama_brg')),
        'tgl_beli' => $request->input('tglbeli'),
        'harga' => $request->input('harga'),
        'toko' => strtoupper($request->input('toko')),
        'spesifikasi' => strtoupper($request->input('spesifikasi')),
        'merk_id' => $request->input('merk'),
        'lokasi_id' => $request->input('lokasi'),
        'status_id' => $request->input('status'),
        'pemilik_id' => $request->input('deptbeli'),
        'serialnumber' => strtoupper($request->input('sn')),
        'user1_id' => $user1,
        'user2_id' => $user2,
        // 'dept_user' => $dept,
      ]);
    }else{
      $this->validate($request, [ 'mtc_note' => 'required' ],[ 'mtc_note.required' => 'Tidak boleh kosong.' ]);

      $update->update([
        'status_id' => $request->input('status'),
        'mtc_note' => strtoupper($request->input('mtc_note')),
      ]);
    }

    if(!$update){ return response()->json(['message' => 'Update gagal'], 500); }
    return response()->json(['message' => 'Update berhasil'], 200);
  }

  public function handleHistory($id)
  {
    $code = str_replace('-', ' ', $id);
    return AppGainventarisLog::where('gainventaris_id', $id)
      ->orderBy('created_at', 'DESC')
      ->with('creator', 'user1namenew', 'user1nameold', 'user2nameold', 'user2namenew', 'locnamenew', 'locnameold', 'merknamenew', 'merknameold')
      ->paginate(5);
  }

  public function handleMarkingSell(Request $request, $id)
  {
    $hargajual = $request->input('hargajual');
    $data = AppGainventaris::find($id)->load('merkname');
    $pemilikname = '';
    if($data->pemilik_id == 1){
      $pemilikname = 'GA';
    }elseif($data->pemilik_id == 2){
      $pemilikname = 'IT';
    }elseif($data->pemilik_id == 3){
      $pemilikname = 'HSE';
    }

    $hist = AppGainventarisLog::where('gainventaris_id', $id)->get();
    $qrname = $data->qrcode;
    $storesell = AppGainventarisSell::create([
      'ex_kd_brg' => $data->kd_brg,
      'nama_brg' => $data->nama_brg,
      'tgl_beli' => $data->tgl_beli,
      'harga_beli' => $data->harga,
      'toko' => $data->toko,
      'spesifikasi' => $data->spesifikasi,
      'serialnumber' => $data->serialnumber,
      'pemilik' => $pemilikname,
      'merk' => $data->merkname->name,
      'harga_jual' => $hargajual,
      'history' => $hist,
    ]);

    if($storesell){
      $data->delete();
      AppGainventarisLog::where('gainventaris_id', $id)->delete();
      unlink(storage_path('app/public/app_inventaris/qrcode/'.$qrname));
    }

    if(!$data){ return response()->json(['message' => 'Menandai '. $data->kd_brg.' gagal'], 500); }
    return response()->json(['message' => 'Menandai '. $data->kd_brg.' berhasil'], 200);
  }

  public function handleDataScan($id)
  {
    $data = AppGainventaris::find($id)->first();
  }

  public function test()
  {
    $cari = request('q');
    $authuserdeptid = request('authuserdeptid');
    $authuseradmin = request('authuseradmin');
    return AppGainventaris::where(function ($query) use ($cari, $authuseradmin, $authuserdeptid){ $query
      ->where(function ($query) use ($authuseradmin, $authuserdeptid) {
        if(!$authuseradmin || $authuserdeptid == 8){ $query->whereIn('pemilik_id', [1, 2]); }
        else if(!$authuseradmin || $authuserdeptid == 6){ $query->whereIn('pemilik_id', [3]); }
        else if($authuseradmin){ $query->whereNotNull('pemilik_id'); }
      })
      ->where(function ($query) use ($cari) { $query
        ->whereHas('merkname', function($query) use ($cari){
          $query->where('name', 'LIKE', '%'.$cari.'%');
        })
        ->orWhereHas('locname', function($query) use ($cari){
          $query->where('name', 'LIKE', '%'.$cari.'%');
        })
        ->orWhereHas('user1name', function($query) use ($cari){ $query
          ->where('name', 'LIKE', '%'.$cari.'%')
          ->orWhereHas('position', function($query) use ($cari){ $query
            ->where('name', 'LIKE', '%'.$cari.'%'); 
          });
        })
        ->orWhereHas('user2name', function($query) use ($cari){ $query
          ->where('name', 'LIKE', '%'.$cari.'%')
          ->orWhereHas('position', function($query) use ($cari){ $query
            ->where('name', 'LIKE', '%'.$cari.'%'); 
          });
        })
        ->orWhere('kd_brg', 'LIKE', '%'.$cari.'%')
        ->orWhere('nama_brg', 'LIKE', '%'.$cari.'%');
      });
    })
    ->orderBy(request()->sortby, request()->sortbydesc)
    ->with('locname', 'merkname', 'user1name', 'user2name', 'user1name.position.deptname', 'user2name.position.deptname')
  ->paginate(request()->per_page);
  }
}
