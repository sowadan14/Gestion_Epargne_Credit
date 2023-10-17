<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
      
        $user = User::create([
           'EmployeId'=>'1',
           'Email'=>'permission@gmail.com',
           'SuperAdmin'=>'1',
           'password'=>Hash::make('123456789'),
           // 'AnnexeID'=>'1',
           'EntrepriseId'=>'1',
           'DateCreation'=>Carbon::now(),
           // 'SaveNumber'=>'1',
           'ImageUser'=>'',
       ]);
      
        $role = Role::create(['name' => 'Admin', 'EntrepriseId'=>'1']);
       
        $permissions = Permission::pluck('id','id')->all();
     
        $role->syncPermissions($permissions);
       
        $user->assignRole([$role->id]);
        Schema::enableForeignKeyConstraints();
    }
}
