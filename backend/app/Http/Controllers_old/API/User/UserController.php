<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\API\User\AppUser;
use App\Models\API\User\AppUserLogin;
use App\Models\API\Phonebook\AppPhonebookExtension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
  public function handleUserDetail()
  {
    $nik = request()->nik;
    $data = AppUser::where('nik', $nik)
                  ->with('login', 'login.permissions', 'login.roles', 'extphone')
                  ->first();
    return response()->json([ 'message' => $data], 200);
  }

  public function handleListCreateLogin()
  {
    $listExist = AppUserLogin::all()->pluck('nik');
    $listAva = AppUser::whereNotIn('nik', $listExist)->get();
    return $listAva;
  }

  public function handleNikAdmin()
  {
    $base = '9000';
    if(AppUser::where('nik', 'LIKE', $base.'%')->count() > 0){
      $last = AppUser::where('nik', 'LIKE', $base.'%')->first();
      $count = substr($last->nik, 2) +1;
      $code = $base.str_pad($count, 3, '0', STR_PAD_LEFT);
    }else{
      $code = $base.'901';
    }
    return $code;
  }

  public function handleNikExternal()
  {
    $base = '8000';
    if(AppUser::where('nik', 'LIKE', $base.'%')->count() > 0){
      $last = AppUser::where('nik', 'LIKE', $base.'%')->first();
      $count = substr($last->nik, 2) +1;
      $code = $base.str_pad($count, 3, '0', STR_PAD_LEFT);
    }else{
      $code = $base.'801';
    }
    return $code;
  }

  public function handleCreateUser(Request $request)
  {
    $this->validate($request, [
      'nik' => 'required',
      'name' => 'required',
      'password' => 'required|min:8',
      'email' => 'required|email',
      'statusUser' => 'required',
    ],[
      'nik.required' => 'NIK tidak boleh kosong',
      'name.required' => 'Nama tidak boleh kosong',
      'password.required' => 'Password tidak boleh kosong',
      'password.min' => 'Password minimal 8 karakter',
      'email.required' => 'Email tidak boleh kosong',
      'email.email' => 'Format email tidak sesuai',
      'statusUser.required' => 'Pilih tipe User'
    ]);

    $shortnik = substr($request->input('nik'), -3);
    $filter = $request->input('statusUser');
    if($filter == 'Admin'){ 
      $isadmin = 1;
      $permission = ['manage.all'];
    }
    else{ 
      $isadmin = 0; 
      $permission = ["read.Auth", "read.ACL"];
    }

    $user = AppUser::create([
      'nik' => $request->input('nik'),
      'name' => strtoupper($request->input('name')),
      'active' => $request->input('userActive')
    ]);

    $login = AppUserLogin::create([
      's_nik' => $shortnik,
      'nik' => $request->input('nik'),
      'email' => $request->input('email'),
      'password' => Hash::make($request->input('password')),
      'active' => $request->input('loginActive'),
      'admin' => $isadmin
    ]);
    
    if(!$user && !$login){ return response()->json(['message' => 'Data gagal disimpan'], 500); }
    $givepermissionuser = AppUserLogin::where('nik', $request->input('nik'))->first();
    $givepermissionuser->syncPermissions($permission);
    return response()->json(['message' => 'Data berhasil disimpan'], 200);
  }

  public function handleCreateLogin(Request $request)
  {
    /** User Only */
    $this->validate($request, [
      'nik' => 'required',
      'new_password' => 'min:8|required_with:rep_password|same:rep_password',
      'rep_password' => 'min:8',
      'email' => 'required|email',
    ],[
      'nik.required' => 'NIK tidak boleh kosong',
      'email.required' => 'Email tidak boleh kosong',
      'email.email' => 'Format email tidak sesuai',
      'new_password.min' => 'Password minimal 8 karakter',
      'new_password.same' => 'Password / Ulangi password tidak sama',
      'rep_password.min' => 'Password minimal 8 karakter'
    ]);

    $shortnik = substr($request->input('nik'), -3);
    $login = AppUserLogin::create([
      'nik' => $request->input('nik'),
      's_nik' => $shortnik,
      'email' => $request->input('email'),
      'password' => Hash::make($request->input('new_password')),
      'active' => $request->input('loginActive'),
      'admin' => 0
    ]);

    if(!$login){ return response()->json(['message' => 'Data gagal disimpan'], 500); }
    $user = AppUserLogin::where('nik', $request->input('nik'))->first();
    $user->syncPermissions(["read.Auth", "read.ACL"]);
    return response()->json(['message' => 'Data berhasil disimpan'], 200);
  }

  public function handleLogin (Request $request)
  {
    $username = $request->input('username');
    $password = $request->input('password');

    $user = AppUserLogin::select('*')
            ->where(function($query) use ($username) { $query
              ->where('s_nik', 'LIKE', $username)
              ->orWhere('nik', 'LIKE', $username)
              ->orWhere('email', 'LIKE', $username);
            })
            // ->with('detail')
            ->with('detail', 'detail.position', 'detail.position.deptname', 'detail.position.parent.user',
                    'detail.position.children.user')
            ->first();
    if(!$user) { return response()->json(['message' => 'User tidak ditemukan'], 500); }
    if($user->active !== 1) { return response()->json(['message' => 'User tidak aktif'], 500); }
    if($user->detail->active !== 1) { return response()->json(['message' => 'User tidak ditemukan'], 500); }
    if(!Hash::check($password, $user->password)) { return response()->json(['message' => 'Username/Password tidak sesuai'], 500); }

    $token = $user->createToken($user->nik)->plainTextToken;
    $user['ability'] = transformRoleToSubject($user);

    return response()->json([
      'message' => 'Login berhasil',
      'token' => $token,
      'userdata' => $user],
    200);
  }

  public function handleSwitchUser(Request $request, $id)
  {
    $data1 = AppUser::findOrFail($id);
    $rnik = $request->input('nik');
    $data1->active = $request->input('newvalue');
    $data1->save();
    if($rnik !== null){
      $data2 = AppUserLogin::where('nik', $rnik)->first();
      if($data2->active != $request->input('newvalue')){
        $data2->active = $request->input('newvalue');
        $data2->save();
      }
    }
    return response()->json(['message' => 'Data berhasil diupdate.'], 200);
  }

  public function handleSwitchLogin(Request $request, $nik)
  {
    $data2 = AppUserLogin::findOrFail($nik);
    $data2->active = $request->input('newvalue');
    $data2->save();
    return response()->json(['message' => 'Data berhasil diupdate.'], 200);
  }

  public function handleLogout(Request $request)
  {
    $logout = $request->user()->currentAccessToken()->delete();
    if(!$logout) { return response()->json(['message' => 'SignOut Gagal.'], 500); }
    return response()->json(['message' => 'Logout berhasil.'], 200);
  }

  public function handleListAllUser()
  {
    $cari = request()->q;
    $initial = request()->initialpage;
    // return $initial;

    $data = AppUser::with('login', 'position', 'position.deptname')
      ->where(function ($query) use ($cari, $initial){
        $query->where(function ($query2) use ($cari){
          $query2->where('name', 'LIKE', '%'.$cari.'%')
            ->orWhere('nik', 'LIKE', '%'.$cari.'%')
            ->orWhereHas('login', function ($query3) use ($cari){
              $query3->where('email', 'LIKE', '%'.$cari.'%')->orWhere('s_nik', 'LIKE', $cari);
            })
            ->orWhereHas('position', function ($query4) use ($cari){
              $query4->where('name', 'LIKE', '%'.$cari.'%');
            })
            ->orWhereHas('position.deptname', function ($query5) use ($cari){
              $query5->where('name', 'LIKE', '%'.$cari.'%');
          });
        })
        ->when($initial == 'raport', function($query5){
          $query5->where('nik', '>', '1000000')->where('nik', '<', '6000000');
        });
      })
      ->orderBy(request()->sortby, request()->sortbydesc)
      ->paginate(request()->per_page);
    return response()->json(['message' => $data], 200);
  }

  public function handleUpdateInfo(Request $request, $id)
  {
    return $request->all();
    if($request->input('ext') || $request->input('extarea')){
      $extno = AppPhonebookExtension::updateOrCreate(
        ['id' => $request->input('extid')],
        ['user_id' => $request->input('userid'), 'ext' => $request->input('ext'), 'lokasi_id' => $request->input('extarea')],
      );
    }

    $user = AppUser::find($request->input('userid'));
    if($request->input('userActive') == '0'){
      $user->update([
        'name' => $request->input('name'),
        'position_id' => $request->input('jabatan_id'),
        'avatar' => $request->input('avatar'),
        'active' => $request->input('userActive'),
      ]);
      $login = $user->login;
      $login->update([
        'active' => $request->input('userActive'),
      ]);
    }else{
      $user->update([
        'name' => $request->input('name'),
        'position_id' => $request->input('jabatan_id'),
        'avatar' => $request->input('avatar'),
        'active' => $request->input('userActive'),
      ]);
    }
    return response()->json(['message'=> 'Data berhasil diperbarui']);
  }

  public function handleUpdateInfo2(Request $request, $id)
  {
    $this->validate($request, [
      'ext' => 'required_with:extarea',
      'extarea' => 'required_with:ext'
    ],[
      'ext.required.with' => 'Extension Area tidak boleh kosong',
      'extarea.required.with' => 'Extension tidak boleh kosong'
    ]);

    $user = AppUser::find($request->userid);
    $pathavatar = storage_path('app/public/app_user/avatar/');

    if($request->hasFile('avatar') && $request->file('avatar')){
      $this->validate($request, [
        'avatar' => 'mimes:jpeg,jpg,png|max:2048|nullable',
      ],[
        'avatar.mimes' => 'File harus tipe JPEG, JPG, PNG',
        'avatar.max' => 'Ukuran file melebihi 2MB',
      ]);

      /** remove old file */
      $oldavatarname = $user->avatar;
      if($oldavatarname != '' && $oldavatarname != null){
        $patholavatar = $pathavatar.$oldavatarname;
        unlink($patholavatar);
      }
      /** store new file */
      $fileavatar = $request->file('avatar');
      $today = Carbon::now()->format('ymd_hi');
      $filename = $request->nik.'-'.$today.'.'.$fileavatar->getClientOriginalExtension();
      $storefile = $fileavatar->move($pathavatar, $filename);
    }else{
      $filename = $request->avatar;
      if($filename == '' || $filename == null){
        /** remove old file */
        $oldavatarname = $user->avatar;
        if($oldavatarname != '' && $oldavatarname != null){
          $patholavatar = $pathavatar.$oldavatarname;
          unlink($patholavatar);
        }
      }
    }
    // $user = AppUser::find($request->userid);
    if($request->userActive == '0'){
      $user->update([
        'name' => $request->name,
        'avatar' => $filename,
        'active' => $request->userActive,
      ]);
      $login = $user->login;
      $login->update([
        'active' => $request->userActive,
      ]);
    }else{
      $user->update([
        'name' => $request->name,
        'avatar' => $filename,
        'active' => $request->userActive,
      ]);
    }

    if($request->ext && $request->extarea){
      AppPhonebookExtension::updateOrCreate(
        ['id' => $request->extid],
        ['user_id' => $request->userid, 'ext' => $request->ext, 'lokasi_id' => $request->extarea],
      );
    }
    
    return response()->json(['message'=> 'Data berhasil diperbarui']);
  }

  public function handleUpdateAccount(Request $request, $nik)
  {
    if($request->input('cur_password') !== null){
      /** 'can update data and password' */
      $data = AppUserLogin::where('nik', $nik)->first();
      if(!Hash::check($request->input('cur_password'), $data->password)) { return response()->json(['errors' => ['cur_password' => ['message' => 'Password tidak sesuai']]], 500); }
      $this->validate($request, [
        'new_password' => 'min:8|required_with:rep_password|same:rep_password',
        'rep_password' => 'min:8',
        'email' => 'required|email',
      ],[
        'new_password.min' => 'Password minimal 8 karakter',
        'new_password.same' => 'Password / Ulangi password tidak sama',
        'rep_password.min' => 'Password minimal 8 karakter',
        'email.required' => 'Email tidak boleh kosong',
        'email.email' => 'Format email tidak sesuai',
      ]);
      $data->email = $request->input('email');
      $data->password = Hash::make($request->input('new_password'));
      $data->active = $request->input('loginActive');
      $data->save();
      if(!$data) { return response()->json(['message' => 'Data gagal diupdate'], 500); }
      return response()->json(['message' => 'Data berhasil diupdate'], 200);
    }else{
      /** 'only update data' */ 
      $data = AppUserLogin::where('nik', $nik)->first();
      $this->validate($request, [
        'email' => 'required|email',
      ],[
        'email.required' => 'Email tidak boleh kosong',
        'email.email' => 'Format email tidak sesuai',
      ]);
      $data->email = $request->input('email');
      $data->active = $request->input('loginActive');
      $data->save();
      if(!$data) { return response()->json(['message' => 'Data gagal diupdate'], 500); }
      return response()->json(['message' => 'Data berhasil diupdate'], 200);
    }
  }

  public function handleUserResetPassword(Request $request, $nik)
  {
    $user = AppUserLogin::where('nik', $nik)->first();
    $password = $request->resetpassword;
    $resetpassword = Hash::make($password);
    $user->password = $resetpassword;
    $user->save();
    if(!$user) { return response()->json(['message' => 'Reset password gagal.'], 500); }
    return response()->json(['message' => 'Password berhasil direset, password baru: '.$password.'.'], 200);
  }

  // public function teetttss(Request $request, $id)
  public function teetttss()
  {
    // return AppUser::where('nik', 1980056)->with(
    //   'position', 'position.deptname', 'position.parent', 'position.user',
    //   'position.children', 'position.children.deptname', 'position.parent.deptname',
    //   'position.children.user', 'position.parent.user')
    //   ->get();
    return AppUserLogin::where('s_nik', '056')->with(
        'detail.position', 'detail.position.deptname', 'detail.position.parent', 'detail.position.user',
        'detail.position.children', 'detail.position.children.deptname', 'detail.position.parent.deptname',
        'detail.position.children.user', 'detail.position.parent.user')
        ->get();
  }
}
