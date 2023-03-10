<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Http\Requests\UpdateProfile;
use Illuminate\Support\Facades\Hash;

use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

  public function listUsers(Request $request) {
    if(!$request->ajax()) { return false;}
    $filters = [];
    $filters[] = ['id','>',1];
    if($request->email) $filters[] = ['email','LIKE',"%$request->email%"];
    if($request->name) $filters[] = ['name','LIKE',"%$request->name%"];

    $listUser = User::with('roles')->where($filters)->orderByDesc('id')->paginate(10);
    return $listUser;
  }

  public function listSellers(Request $request) {
    if(!$request->ajax()) { return false;}
    $listSellers = User::with('roles')->where('id', '>', 1)->where('is_seller', 1)->get();
    return $listSellers;
  }

  public function store(StoreUser $request) {
    if(!$request->ajax()) { return false;}
    $user = new User();
    $user->name = $request->name;
    $user->nickName = $request->nickName;
    $user->email = $request->email;
    $user->cuota = $request->cuota ?? 0;
    $user->is_seller = $request->is_seller;
    $user->password = Hash::make("fulltecnologia".now()->format('Y'));
    $user->save();
    return $user;
  }

  public function update(UpdateUser $request) {
    if(!$request->ajax()) { return false;}
    $user = User::find($request->id);
    $user->name = $request->name;
    $user->nickName = $request->nickName;
    $user->email = $request->email;
    $user->cuota = $request->cuota ?? 0;
    $user->is_seller = $request->is_seller;
    $user->password = Hash::make($request->password);
    $user->save();
    return response()->json(['message' => 'Updated']);
  }

  public function profileData() {
    $user = Auth::user();
    return $user;
  }

  public function updateProfile(UpdateProfile $request) {
    $user = User::find($request->id);
    $user->nickName = $request->nickName;
    $user->email = $request->email;

    if($request->hasChangePassword) {
      if(!Hash::check($request->currentPassword, $user->password)) throw new Exception('La contraseña actual no es correcta');
      $user->password = Hash::make($request->newPassword);
    }
    $user->save();

    return $user;
  }
}
