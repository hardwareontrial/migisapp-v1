<?php

namespace App\Http\Controllers\API\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\News\AppNewsCategory;
use App\Models\API\News\AppNews;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
  public function handleCategoryList()
  {
    return AppNewsCategory::all();
  }

  public function handleData()
  {
    $cari = request()->q;
    $data = AppNews::orderBy(request()->sortby, request()->sortbydesc)
                  ->with('creator')
                  ->paginate(request()->per_page);
    
    return response()->json(['message' => $data], 200);
  }

  public function handleNewsShowing()
  {
    $cari = request()->q;
    $data = AppNews::where('status', 2)
                  ->orderBy(request()->sortby, request()->sortbydesc)
                  ->with('creator')
                  ->paginate(request()->per_page);
    
    return response()->json(['message' => $data], 200);
  }

  public function handleStore(Request $request)
  {
    $this->validate($request, [
      'title' => 'required',
      'category' => 'required',
      'slug' => 'required',
      'status' => 'required',
      'content' => 'required',
    ],
    [
      'title.required' => 'Judul tidak boleh kosong',
      'category.required' => 'Pilih min. 1 kategori',
      'slug.required' => 'Slug tidak boleh kosong',
      'status.required' => 'Status tidak boleh kosong',
      'content.required' => 'Konten tidak boleh kosong'
    ]);

    $data = AppNews::create([
      'title' => $request->input('title'),
      'category' => $request->input('category'),
      'slug' => $request->input('slug'),
      'status' => $request->input('status'),
      'content' => $request->input('content'),
      'coverimage' => $request->input('coverimage'),
      'created_by' => $request->input('creator'),
    ]);

    if(!$data){ return response()->json(['message' => 'Data gagal disimpan.'], 500); }
    return response()->json(['message' => 'Data disimpan.'], 200);
  }

  public function handleDetailbySlug($id)
  {
    return AppNews::where('slug', $id)->first()->load('creator');
  }

  public function handleUpdate(Request $request, $id)
  {
    $this->validate($request, [
      'title' => 'required',
      'category' => 'required',
      'slug' => 'required',
      'status' => 'required',
      'content' => 'required',
    ],
    [
      'title.required' => 'Judul tidak boleh kosong',
      'category.required' => 'Pilih min. 1 kategori',
      'slug.required' => 'Slug tidak boleh kosong',
      'status.required' => 'Status tidak boleh kosong',
      'content.required' => 'Konten tidak boleh kosong'
    ]);

    $data = AppNews::find($id);
    $data->update([
      'title' => $request->input('title'),
      'category' => $request->input('category'),
      'slug' => $request->input('slug'),
      'status' => $request->input('status'),
      'content' => $request->input('content'),
      'coverimage' => $request->input('coverimage'),
    ]);

    if(!$data){ return response()->json(['message' => 'Data gagal disimpan.'], 500); }
    return response()->json(['message' => 'Data disimpan.'], 200);
  }

  public function handleDelete($id)
  {
    $data = AppNews::find($id);
    $data->delete();

    if(!$data){ return response()->json(['message' => 'Data gagal dihapus.'], 500); }
    return response()->json(['message' => 'Data berhasil dihapus.'], 200);
  }

  public function urlcutter()
  {
    //
  }
}
