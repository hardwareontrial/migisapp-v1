<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\API\User\AppUserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use stdClass;

class RolePermissionController extends Controller
{
  public function __construct()
  {
    // $this->middleware(['permission:usermgt.add'], ['except' => 'handleListPermission']);
  }

  public function handleListRole()
  {
    // $roles = Role::all();
    // $roles = 'Roles List';
    // return response()->json(['message' => $roles], 200);
  }

  public function handleListPermission()
  {
    if(request()->f == 'table-list-permission'){
      $cari = request()->q;
      $data = Permission::orderBy(request()->sortby, request()->sortbydesc)
                        ->where(function ($query) use ($cari){
                          $query->where('text', 'LIKE', '%'.$cari.'%');
                        })
                        ->paginate(request()->per_page);
      return response()->json(['message' => $data], 200);
    }
    if(request()->f == 'form-list-permission'){
      // $data = Permission::all();
      // return response()->json(['message' => $data], 200);
      $p = Permission::all();
      $result = array();
      foreach ($p as $pres){
        $result[$pres['group_name']][] = $pres;
      }
      return response()->json(['message' => $result], 200);
    }
  }

  public function handleAssignRole(Request $request)
  {
    //
  }

  public function handleAssignPermission(Request $request, $nik)
  {
    $user = AppUserLogin::where('nik', $nik)->first();
    $permissions = $request->input('selectedPermission');
    $user->syncPermissions($permissions);
    return response()->json(['message' => 'Data berhasil diperbarui']);
  }

  public function handleAssignPermissionFromRegisterLogin($params)
  {
    if($params->admin == 0){ $assign = $params->givePermissionTo(['read.Auth', 'read.ACL']); }
    else{ $assign = $params->givePermissionTo('manage.all'); }

    if($assign){ return true; }
    return false;
  }

  public function handleRoleToSubject($user)
  {
    //
  }

}
