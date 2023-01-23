<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Dummy;
use Illuminate\Http\Request;

class DummyController extends Controller
{
  public function dummy()
  {
    $dummy = Dummy::orderBy(request()->sortby, request()->sortbydesc)
      ->when(request()->q, function($dummy) {
      $dummy = $dummy->where('title', 'LIKE', '%'.request()->q.'%')
                    ->orWhere('author', 'LIKE', '%'.request()->q.'%')
                    ->orWhere('category', 'LIKE', '%'.request()->q.'%');
      })->paginate(request()->per_page);
    return response()->json(['message' => $dummy], 200);
  }

}
