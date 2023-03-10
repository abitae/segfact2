<?php

use App\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $user = new User();
      $user->name = "Super administrador";
      $user->email = "ch4v3zfredy@gmail.com";
      $user->email_verified_at = Carbon::now();
      $user->password = Hash::make("{admin123..}");
      $user->remember_token = Str::random(10);
      $user->save();

      $this->call(RolePermissionTableSeeder::class);
      $this->call(TypeDocumentTableSeeder::class);
      $this->call(DocumentStateTableSeeder::class);


      // $user->assignRole(1);

    }
}
