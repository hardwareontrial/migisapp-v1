<?php

namespace App\Http\Controllers\API\Phonebook;

use App\Http\Controllers\Controller;
use App\Models\API\Phonebook\AppPhonebook;
use App\Models\API\Phonebook\AppPhonebookDetail;
use App\Models\API\Phonebook\AppPhonebookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PhonebookController extends Controller
{
  public function handleSourceData()
  {
    $cari = request()->q;
    $data = AppPhonebook::orderBy(request()->sortby, request()->sortbydesc)
      ->whereHas('detail', function($query) use ($cari){
        $query->where(function ($query) use ($cari){
          $query->where('pic', 'LIKE', '%'.$cari.'%')
                ->orWhere('number', 'LIKE', '%'.$cari.'%');
        })
        ->orWhere('name', 'LIKE', '%'.$cari.'%')
        ->orWhere('address', 'LIKE', '%'.$cari.'%')
        ->orWhere('company_name', 'LIKE', '%'.$cari.'%')
        ->orWhere('company_address', 'LIKE', '%'.$cari.'%')
        ->orWhere('email', 'LIKE', '%'.$cari.'%')
        ->orWhere('note', 'LIKE', '%'.$cari.'%');
      })
      ->with('detail')
      ->paginate(request()->per_page);

    return response()->json(['message' => $data], 200);
  }

  public function handleStore(Request $request)
  {
    if($request->input('typeinput') == 1){
      $this->validate($request,
        [
          'name' => 'required|max:50',
          'address' => 'required|max:100',
        //   'email' => 'email',
          'keterangan' => 'max:100',
          'inputs' => 'array',
          'inputs.*.number' => 'required|max:15',
          'inputs.*.pic' => 'max:30',
        ],
        [
          'name.required' => 'Nama wajib diisi.',
          'name.max' => 'Nama melebihi 50 karakter.',
          'address.required' => 'Alamat wajib diisi.',
          'address.max' => 'Alamat melebihi 100 karakter.',
          'keterangan.max' => 'Keterangan melebihi 100 karakter.',
          // 'email.email' => 'Format email tidak sesuai.',
          'inputs.*.number.required' => 'Input Nomor wajib diisi.',
          'inputs.*.number.max' => 'Input Nomor melebihi 15 angka.',
          'inputs.*.pic.max' => 'Input PIC melebihi 30 karakter.',
        ]
      );
    }
    if($request->input('typeinput') === 2){
      $this->validate($request,
        [
          'company' => 'required|max:50',
          'company_address' => 'required|max:100',
          // 'email' => 'email',
          'keterangan' => 'max:100',
          'inputs' => 'array',
          'inputs.*.number' => 'required|max:15',
          'inputs.*.pic' => 'required|max:30',
        ],
        [
          'company.required' => 'Nama Perusahaan wajib diisi.',
          'company.max' => 'Nama Perusahaan melebihi 100 karakter.',
          'company_address.required' => 'Alamat Perusahaan wajib diisi.',
          'company_address.max' => 'Alamat Perusahaan melebihi 100 karakter.',
          'keterangan.max' => 'Keterangan melebihi 100 karakter.',
          // 'email.email' => 'Format email tidak sesuai.',
          'inputs.*.number.required' => 'Input Nomor wajib diisi.',
          'inputs.*.number.max' => 'Input Nomor melebihi 15 angka.',
          'inputs.*.pic.required' => 'Input PIC wajib diisi.',
          'inputs.*.pic.max' => 'Input PIC melebihi 30 karakter.',
        ]
      );
    }

    $user = Auth::guard('sanctum')->user()->detail;

    DB::beginTransaction();
      $store = new AppPhonebook;
      $store->type = $request->input('typeinput');
      $store->name = $request->input('name');
      $store->address = $request->input('address');
      $store->city = $request->input('city');
      $store->company_name = $request->input('company');
      $store->company_address = $request->input('company_address');
      $store->email = $request->input('email');
      $store->note = $request->input('keterangan');
      $store->created_by = $user->id;
      $store->save();

      $detail = $request->input('inputs');
      foreach ($detail as $d)
      {
        DB::beginTransaction();
          $storedetail = new AppPhonebookDetail;
          $storedetail->phonebook_id = $store->id;
          $storedetail->number = $d['number'];
          $storedetail->pic = $d['pic'];
          $storedetail->save();
        DB::commit();
      }

      $log = new AppPhonebookLog;
      $log->phonebook_id = $store->id;
      $log->phonebook_type = $request->input('typeinput');
      $log->activity = 'menambah data baru';
      $log->user_id = $user->id;
      $log->save();
    DB::commit();

    return response()->json(['message' => 'Data berhasil disimpan.'], 200);
  }

  public function handleShow($id)
  {
    return AppPhonebook::where('id', $id)->with('detail')->first();
  }

  public function handleUpdate(Request $request, $id)
  {
    if($request->input('typeinput') == 1){
      $this->validate($request,
        [
          'name' => 'required|max:50',
          'address' => 'max:100',
          // 'email' => 'email',
          'keterangan' => 'max:100',
          'inputs' => 'array',
          'inputs.*.number' => 'required|max:15',
          'inputs.*.pic' => 'max:30',
        ],
        [
          'name.required' => 'Nama wajib diisi.',
          'name.max' => 'Nama melebihi 50 karakter.',
          // 'address.required' => 'Alamat wajib diisi.',
          'address.max' => 'Alamat melebihi 100 karakter.',
          'keterangan.max' => 'Keterangan melebihi 100 karakter.',
          //   'email.email' => 'Format email tidak sesuai.',
          'inputs.*.number.required' => 'Input Nomor wajib diisi.',
          'inputs.*.number.max' => 'Input Nomor melebihi 15 angka.',
          'inputs.*.pic.max' => 'Input PIC melebihi 30 karakter.',
        ]
      );
    }
    if($request->input('typeinput') === 2){
      $this->validate($request,
        [
          'company' => 'required|max:50',
          'company_address' => 'max:100',
        //   'email' => 'email',
          'keterangan' => 'max:100',
          'inputs' => 'array',
          'inputs.*.number' => 'required|max:15',
          'inputs.*.pic' => 'required|max:30',
        ],
        [
          'company.required' => 'Nama Perusahaan wajib diisi.',
          'company.max' => 'Nama Perusahaan melebihi 100 karakter.',
          // 'company_address.required' => 'Alamat Perusahaan wajib diisi.',
          'company_address.max' => 'Alamat Perusahaan melebihi 100 karakter.',
          'keterangan.max' => 'Keterangan melebihi 100 karakter.',
        //   'email.email' => 'Format email tidak sesuai.',
          'inputs.*.number.required' => 'Input Nomor wajib diisi.',
          'inputs.*.number.max' => 'Input Nomor melebihi 15 angka.',
          'inputs.*.pic.required' => 'Input PIC wajib diisi.',
          'inputs.*.pic.max' => 'Input PIC melebihi 30 karakter.',
        ]
      );
    }
    $user = Auth::guard('sanctum')->user()->detail;

    $update = AppPhonebook::find($id);
    $update->update([
      'name' => $request->input('name'),
      'address' => $request->input('address'),
      'city' => $request->input('city'),
      'company_name' => $request->input('company'),
      'company_address' => $request->input('company_address'),
      'email' => $request->input('email'),
      'note' => $request->input('keterangan'),
    ]);

    $detail = $request->input('inputs');
    foreach ($detail as $d)
    {
      $updatedetail = AppPhonebookDetail::updateOrCreate(
        [ 'id' => $d['id'], 'phonebook_id' => $id ],
        [ 'number' => $d['number'], 'pic' => $d['pic'] ]
      );
    }
    DB::beginTransaction();
      $log = new AppPhonebookLog;
      $log->phonebook_id = $id;
      $log->phonebook_type = $update->type;
      $log->activity = 'merubah/menambah data';
      $log->user_id = $user->id;
      $log->save();
    DB::commit();
    return response()->json(['message' => 'Data diupdate'], 200);
  }

  public function handleDeleteDetail($id, $detailid)
  {
    $user = Auth::guard('sanctum')->user()->detail;
    $type = AppPhonebook::where('id', $id)->first();
    AppPhonebookDetail::where('id', $detailid)->where('phonebook_id', $id)->delete();
    DB::beginTransaction();
      $log = new AppPhonebookLog;
      $log->phonebook_id = $id;
      $log->phonebook_type = $type->type;
      $log->activity = 'menghapus data';
      $log->user_id = $user->id;
      $log->save();
    DB::commit();
    return response()->json(['message' => 'Nomor dihapus'], 200);
  }

  public function handleDelete($id)
  {
    $data = AppPhonebook::find($id);
    $data->detail()->delete();
    $data->delete();

    return response()->json(['message' => 'Data dihapus.']);
  }
}
