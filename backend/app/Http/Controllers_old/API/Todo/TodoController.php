<?php

namespace App\Http\Controllers\API\Todo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\Todo\AppTodo;

class TodoController extends Controller
{
  public function handleData()
  {
    $typeuser = auth('sanctum')->user()->admin;
    $iduser = auth('sanctum')->user()->detail->id;

    $filter = request()->filter;
    $tag = request()->tag;
    $search = request()->search;

    if($filter === 'important'){ 
      $important = 1;
      $completed = '';
      $deleted = '';
    }elseif($filter === 'completed'){
      $important = '';
      $completed = 1;
      $deleted = '';
    }
    elseif($filter === 'deleted'){ 
      $important = '';
      $completed = '';
      $deleted = 1;
    }
    else {
      $important = '';
      $completed = 0;
      $deleted = 0;
    }
    
    $data = AppTodo::where('isImportant', 'LIKE', '%'.$important.'%')
                    ->where('isComplete', 'LIKE', '%'.$completed.'%')
                    ->where('isDeleted', 'LIKE', '%'.$deleted.'%')
                    ->where('tags', 'LIKE', '%'.$tag.'%')
                    ->where(function($query) use ($search) {
                      $query->where('title', 'LIKE', '%'.$search.'%')
                            ->orWhere('detail', 'LIKE', '%'.$search.'%')
                            ->orWhereHas('requestorname', function($query) use ($search){
                              $query->where('name', 'LIKE', '%'.$search.'%');
                            })
                            ->orWhereHas('assigneename', function($query) use ($search){
                              $query->where('name', 'LIKE', '%'.$search.'%');
                            });
                    })
                    ->with('requestorname', 'assigneename')
                    ->orderBy('dueDate', 'ASC')
                    ->get();
    return $data;

    // if($typeuser === 1){
    //   return AppTodo::with('assigneename', 'requestorname', 'creatorname')->get();
    // }else{
    //   return AppTodo::where('requestor_id', $iduser)
    //                 ->where('assignee_id', $iduser)
    //                 ->where('created_by', $iduser)
    //                 ->with('requestorname', 'assigneename', 'creatorname')
    //                 ->get();
    // }
    // return response()->json(['message' => $data], 200);
  }

  public function handleStore(Request $request)
  {
    $this->validate($request, [
      'title' => 'required',
      'assignee' => 'required',
      'requestor' => 'required',
      'duedate' => 'required',
      'tags' => 'required',
      'detail' => 'required',
    ],
    [
      'title.required' => 'Judul tidak boleh kosong.',
      'assignee.required' => 'Pilih salah satu User.',
      'requestor.required' => 'Pilih salah satu User.',
      'duedate.required' => 'Tanggal tidak boleh kosong.',
      'tags.required' => 'Pilih minimal 1 tag.',
      'detail.required' => 'Masukkan detail.',
    ]);

    $store = AppTodo::create([
      'title' => $request->input('title'),
      'dueDate' => $request->input('duedate'),
      'detail'  => $request->input('detail'),
      'assignee_id' => $request->input('assignee'),
      'tags' => $request->input('tags'),
      'requestor_id' => $request->input('requestor'),
      'isComplete' => $request->input('isComplete'),
      'isImportant' => $request->input('isImportant'),
      'isDeleted' => $request->input('isDeleted'),
      'created_by' => auth('sanctum')->user()->detail->id
    ]);

    if(!$store){ return response()->json([ 'message' => 'Gagal Tersimpan.'], 500); }
    return response()->json([ 'message' => 'Berhasil Tersimpan.'], 200);
  }

  public function detail()
  {
    //
  }

  public function handleUpdate(Request $request, $id)
  {
    $this->validate($request, [
      'title' => 'required',
      'assignee' => 'required',
      'requestor' => 'required',
      'duedate' => 'required',
      'tags' => 'required',
      'detail' => 'required',
    ],
    [
      'title.required' => 'Judul tidak boleh kosong.',
      'assignee.required' => 'Pilih salah satu User.',
      'requestor.required' => 'Pilih salah satu User.',
      'duedate.required' => 'Tanggal tidak boleh kosong.',
      'tags.required' => 'Pilih minimal 1 tag.',
      'detail.required' => 'Masukkan detail.',
    ]);
    $data = AppTodo::find($id);
    $data->update([
      'title' => $request->input('title'),
      'dueDate' => $request->input('duedate'),
      'detail'  => $request->input('detail'),
      'assignee_id' => $request->input('assignee'),
      'tags' => $request->input('tags'),
      'requestor_id' => $request->input('requestor'),
      'isComplete' => $request->input('isComplete'),
      'isImportant' => $request->input('isImportant'),
      'isDeleted' => $request->input('isDeleted'),
    ]);
    if(!$data){ return response()->json([ 'message' => 'Gagal Tersimpan.'], 500); }
    return response()->json([ 'message' => 'Berhasil Tersimpan.'], 200);
  }

  public function markImportant()
  {
    //
  }

  public function markComplete(Request $request, $id)
  {
    $data = AppTodo::find($id);
    $data->update([
      'isComplete' => $request->input('newstatus'),
    ]);
    if(!$data){ return response()->json([ 'message' => 'Gagal Tersimpan.'], 500); }
    return response()->json([ 'message' => 'Berhasil Tersimpan.'], 200);
  }

  public function markDelete(Request $request, $id)
  {
    $data = AppTodo::find($id);
    $data->update([
      'isDeleted' => $request->input('newstatus'),
    ]);
    if(!$data){ return response()->json([ 'message' => 'Gagal Tersimpan.'], 500); }
    return response()->json([ 'message' => 'Berhasil Tersimpan.'], 200);
  }
}
