<?php

namespace App\Http\Controllers\API\Phonebook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\Phonebook\AppPhonebookExtension;


class PhonebookExtensionController extends Controller
{
  public function handleDataSource()
  {
    $cari = request()->q;
    $data = AppPhonebookExtension::orderBy(request()->sortby, request()->sortbydesc)
      ->whereHas('user', function($query) use ($cari){
        $query->where(function ($query) use ($cari){
          $query->where('name', 'LIKE', '%'.$cari.'%');
        })
        ->orWhere('ext', 'LIKE', '%'.$cari.'%');
      })
      ->with('user', 'user.position')
      ->paginate(request()->per_page);

    return response()->json(['message' => $data], 200);
  }
  
  public function handleStore(Request $request)
  {
    $this->validate($request, [
      'user_id' => 'required',
      'ext' => 'required',
      'lokasi_id' => 'required'
    ],
    [
      'user_id.required' => 'Pilih satu User',
      'ext.required' => 'Masukkan Ext',
      'lokasi_id.required' => 'Pilih satu lokasi'
    ]);

    $data = AppPhonebookExtension::create([
      'user_id' => $request->input('user_id'),
      'ext' => $request->input('ext'),
      'lokasi_id' => $request->input('lokasi_id'),
    ]);

    if(!$data) { return response()->json(['message' => 'Data gagal disimpan'], 500); }
    return response()->json(['message' => 'Data berhasil disimpan'], 200);
  }
  
  public function handleShow($id)
  {
    return AppPhonebookExtension::find($id)->load('user');
  }
  
  public function handleUpdate(Request $request, $id)
  {
    $this->validate($request, [
      'user_id' => 'required',
      'ext' => 'required',
      'lokasi_id' => 'required'
    ],
    [
      'user_id.required' => 'Pilih satu User',
      'ext.required' => 'Masukkan Ext',
      'lokasi_id.required' => 'Pilih satu lokasi'
    ]);

    $data = AppPhonebookExtension::find($id);
    $data->update([
      'user_id' => $request->input('user_id'),
      'ext' => $request->input('ext'),
      'lokasi_id' => $request->input('lokasi_id'),
    ]);

    if(!$data) { return response()->json(['message' => 'Data gagal disimpan'], 500); }
    return response()->json(['message' => 'Data berhasil disimpan'], 200);
  }

  public function handleDelete($id)
  {
    $data = AppPhonebookExtension::find($id);
    $data->delete();

    if(!$data) { return response()->json(['message' => 'Data gagal dihapus'], 500); }
    return response()->json(['message' => 'Data berhasil dihapus'], 200);
  }
}
