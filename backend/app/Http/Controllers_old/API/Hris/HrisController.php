<?php

namespace App\Http\Controllers\API\Hris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\HRIS\AppHrisDepartment;
use App\Models\API\HRIS\AppHrisPosition;
use App\Models\API\User\AppUser;
use App\Models\API\User\AppUserLogin;

class HrisController extends Controller
{
  public function handleListDepartment()
  {
    // return AppHrisDepartment::with('positions', 'positions.children')->get();
    $cari = request()->q;
    $data = AppHrisDepartment::orderBy(request()->sortby, request()->sortbydesc)
                            ->with('positions')
                            ->whereHas('positions', function ($query) use ($cari){
                              $query->where('name', 'LIKE', '%'.$cari.'%');
                            })
                            ->orWhere(function ($query) use ($cari){
                              $query->where('name', 'LIKE', '%'.$cari.'%');
                            })
                            ->paginate(request()->per_page);
    return response()->json(['message' => $data], 200);
  }

  public function handleListPosition()
  {
    // return AppHrisPosition::with('children', 'deptname', 'children.deptname', 'parent', 'parent.deptname')->get();
    $cari = request()->q;
    $data = AppHrisPosition::orderBy(request()->sortby, request()->sortbydesc)
                          ->with('user', 'deptname')
                          ->whereHas('user', function ($query) use ($cari){
                            $query->where('name', 'LIKE', '%'.$cari.'%');
                          })
                          ->orWhereHas('deptname', function ($query) use ($cari){
                            $query->where('name', 'LIKE', '%'.$cari.'%');
                          })
                          ->orWhere(function ($query) use ($cari){
                            $query->whereNotIn('id', [1])
                                  ->where('name', 'LIKE', '%'.$cari.'%');
                          })
                          ->paginate(request()->per_page);
    return response()->json(['message' => $data], 200);
  }

  public function handleParentChildrenUser()
  {
    return AppUser::where('nik', request()->nik)
                      ->with('login', 'position', 'position.deptname', 'position.parent.user',
                      'position.children.user')
                      ->first();
  }

  public function testhris()
  {
    /** Test Department -> Sub Department -> Position */
    // return AppHrisPosition::whereNotIn('id' , [1])
    //                       ->with('user')
    //                       ->get();

    // return AppUser::where('id', 4)->with('position', 'position.deptname')->get();

    // $user = auth('sanctum')->user()
    //         ->load('detail', 'detail.position', 'detail.position.deptname', 'detail.position.parent', 'detail.position.parent');
    // return $user;

    // return AppUserLogin::where('s_nik', 276)->with('detail', 'detail.position', 'detail.position.deptname', 'detail.position.parent.user',
    //       'detail.position.children.user')->first();

  }
}
