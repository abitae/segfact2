<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
  public function listRoles()
  {
    return Role::with('permissions')->withCount('permissions')->get();
  }

  public function storeRole(Request $request)
  {
    $role = Role::create(['name' => $request->name]);
    return $role;
  }

  public function updateRole(Request $request)
  {
    $role = Role::find($request->id);
    $role->name = $request->name;
    $role->save();
    return $role;
  }

  public function listPermissions()
  {
    return Permission::get();
  }

  public function storePermission(Request $request)
  {
    $permission = Permission::create(['name' => $request->name]);
    return $permission;
  }

  public function updatePermission(Request $request)
  {
    $role = Permission::find($request->id);
    $role->name = $request->name;
    $role->save();
    return $role;
  }

  function assingRoleToUser(Request $request) {
    // return $request->roleIds;
    $user = User::find($request->userId);
    $roles = Role::whereIn('id', $request->roleIds)->get();
    if($request->prevRol) {
      $user->removeRole($request->prevRol);
    }

    if(count($roles)) {
      $user->syncRoles($roles);
    }
    return response()->json('success');
  }


  public function asignPermissionToRol(Request $request) {
    $role = Role::find($request->role_id);
    // dump($role);

    $role->syncPermissions($request->permissions);
    return response()->json(['message' => 'updated']);
  }
}
