<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Schema::disableForeignKeyConstraints();
      Role::truncate();
      Permission::truncate();
      Schema::enableForeignKeyConstraints();

      $administrador = Role::create(['name' => 'administrador']);
      $contabilidad = Role::create(['name' => 'contabilidad']);
      $despacho = Role::create(['name' => 'despacho']);
      $comprador = Role::create(['name' => 'comprador']);
      $vendedor = Role::create(['name' => 'vendedor']);
      $soporte = Role::create(['name' => 'soporte']);

      $permission1 = Permission::create(['name' => 'view client']);
      $permission2 = Permission::create(['name' => 'create client']);
      $permission3 = Permission::create(['name' => 'edit client']);
      $permission4 = Permission::create(['name' => 'update status client']);

      $permission5 = Permission::create(['name' => 'view enterprises']);
      $permission6 = Permission::create(['name' => 'create enterprise']);
      $permission7 = Permission::create(['name' => 'edit enterprise']);
      $permission8 = Permission::create(['name' => 'update status enterprise']);

      $permission9 = Permission::create(['name' => 'view pucharses']);
      $permission10 = Permission::create(['name' => 'create pucharse']);
      $permission11 = Permission::create(['name' => 'edit pucharse']);
      $permission12 = Permission::create(['name' => 'update status pucharse']);
      $permission13 = Permission::create(['name' => 'print report pucharse']);

      $permission14 = Permission::create(['name' => 'view sales']);
      $permission15 = Permission::create(['name' => 'create sale']);
      $permission16 = Permission::create(['name' => 'edit sale']);
      $permission17 = Permission::create(['name' => 'update status de sale']);
      $permission18 = Permission::create(['name' => 'print report de sale']);

      $permission19 = Permission::create(['name' => 'view suppliers']);
      $permission20 = Permission::create(['name' => 'create supplier']);
      $permission21 = Permission::create(['name' => 'edit supplier']);
      $permission22 = Permission::create(['name' => 'update status de supplier']);

      $permission23 = Permission::create(['name' => 'view contacts']);
      $permission24 = Permission::create(['name' => 'create contact']);
      $permission25 = Permission::create(['name' => 'edit contact']);
      $permission26 = Permission::create(['name' => 'update status contact']);

      $permission27 = Permission::create(['name' => 'view licenses']);
      $permission28 = Permission::create(['name' => 'create license']);
      $permission29 = Permission::create(['name' => 'edit license']);
      $permission30 = Permission::create(['name' => 'update status license']);

      $permission31 = Permission::create(['name' => 'view invoice tracking']);
      $permission32 = Permission::create(['name' => 'create invoice tracking']);
      $permission33 = Permission::create(['name' => 'edit invoice tracking']);
      $permission34 = Permission::create(['name' => 'update status invoice tracking']);
      $permission35 = Permission::create(['name' => 'print report invoice tracking']);

      $permission36 = Permission::create(['name' => 'view users']);
      $permission37 = Permission::create(['name' => 'create user']);
      $permission38 = Permission::create(['name' => 'edit user']);
      $permission39 = Permission::create(['name' => 'update status user']);

      $permission40 = Permission::create(['name' => 'view roles']);
      $permission41 = Permission::create(['name' => 'update roles']);

      $permission42 = Permission::create(['name' => 'view series']);
      $permission43 = Permission::create(['name' => 'create serie']);
      $permission44 = Permission::create(['name' => 'edit serie']);
      $permission45 = Permission::create(['name' => 'update status serie']);

      $permission46 = Permission::create(['name' => 'view banks']);
      $permission47 = Permission::create(['name' => 'create bank']);
      $permission48 = Permission::create(['name' => 'edit bank']);
      $permission49 = Permission::create(['name' => 'update status bank']);

      $administrador->syncPermissions([
        $permission1,$permission2,$permission3,$permission4,$permission5,$permission6,$permission7,$permission8,$permission9,
        $permission10,$permission11,$permission12,$permission13,$permission14,$permission15,$permission16,$permission17,$permission18,$permission19,
        $permission20,$permission21,$permission22,$permission23,$permission24,$permission25,$permission26,$permission27,$permission28,$permission29,
        $permission30,$permission31,$permission32,$permission33,$permission34,$permission35,$permission36,$permission37,$permission38,$permission39,
        $permission40,$permission41,$permission42,$permission43,$permission44,$permission45,$permission46,$permission47,$permission48,$permission49,
      ]);

      $user = User::find(1);
      $user->assignRole('administrador');

    }
}
