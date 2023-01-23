<?php

namespace App\Http\Controllers\API\GaInventaris;

use App\Http\Controllers\Controller;
use App\Models\API\GaInventaris\AppGainventaris;
use App\Models\API\General\ListLocation;
use App\Models\API\General\ListMerk;
use App\Models\API\HRIS\AppHrisDepartment;
use App\Models\API\User\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GaInventarisListController extends Controller
{
  public function handleListMerk()
  {
    return ListMerk::all();
  }

  public function handleListLokasi()
  {
    return ListLocation::all();
  }

  public function handleListUser()
  {
    return AppUser::whereNotBetween('nik', [8000000, 9999999])->with('login', 'position')->get();
  }

  public function handleListInventaris()
  {
    return AppGainventaris::with('locname', 'merkname', 'user1name', 'user2name', 'user1name.position.deptname', 'user2name.position.deptname')->get();
  }

  public function handleAddMerk(Request $request)
  {
    $this->validate($request, [ 'name' => 'required' ],[ 'name.required' => 'Input Nama Merk.' ]);
    DB::beginTransaction();
      $merk = new ListMerk;
      $merk->name = strtoupper($request->input('name'));
      $merk->save();
    DB::commit();
    return response()->json(['message' => 'Merk baru berhasil ditambah.'], 200);
  }

  public function handleAddLokasi(Request $request)
  {
    $this->validate($request, [ 'name' => 'required' ],[ 'name.required' => 'Input Nama Lokasi.' ]);
    DB::beginTransaction();
      $merk = new ListLocation;
      $merk->name = strtoupper($request->input('name'));
      $merk->save();
    DB::commit();
    return response()->json(['message' => 'Lokasi baru berhasil ditambah.'], 200);
  }

  public function handleOwnerDept()
  {
    return AppHrisDepartment::all();
  }
}
