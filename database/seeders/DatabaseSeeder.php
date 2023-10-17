<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call(SexeSeeder::class);
        $this->call(OtherDataSeeder::class);
        $this->call(EntrepriseSeeder::class);
        $this->call(PosteSeeder::class);
        $this->call(TypeCompteSeeder::class);
        $this->call(CompteSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CreateAdminUserSeeder::class);
        
        Schema::enableForeignKeyConstraints();
    }
}

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
       
        $user = User::create([
            'AdherentId' => '1',
            'Email' => 'permission@gmail.com',
            'SuperAdmin' => '1',
            'password' => Hash::make('123456789'),
            'EntrepriseId' => '1',
            'ImageUser' => '',
        ]);

        // $role = Role::create(['Nom' => 'Admin', 'EntrepriseId' => '1']);

        // $permissions = Permission::pluck('id', 'id')->all();

        // $role->syncPermissions($permissions);

        // $user->assignRole([$role->id]);
        Schema::enableForeignKeyConstraints();
    }
}

class OtherDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('couleurs')->insert(['text' => '#607D8B', 'value' => '#607D8B']);
        DB::table('couleurs')->insert(['text' => '#967ADC', 'value' => '#967ADC']);
        DB::table('couleurs')->insert(['text' => '#DA4453', 'value' => '#DA4453']);
        DB::table('couleurs')->insert(['text' => '#37BC9B', 'value' => '#37BC9B']);
        DB::table('couleurs')->insert(['text' => '#2196F3', 'value' => '#2196F3']);
        DB::table('couleurs')->insert(['text' => '#00BCD4', 'value' => '#00BCD4']);
        DB::table('couleurs')->insert(['text' => '#E91E63', 'value' => '#E91E63']);
        DB::table('couleurs')->insert(['text' => '#1D2B36', 'value' => '#1D2B36']);
        DB::table('couleurs')->insert(['text' => '#216ac4', 'value' => '#216ac4']);
        DB::table('couleurs')->insert(['text' => '#F57F17', 'value' => '#F57F17']);



        DB::table('fonts')->insert(['text' => 'CoolJazz', 'value' => 'CoolJazz']);
        DB::table('fonts')->insert(['text' => 'Trebuchet MS, sans-serif', 'value' => 'Trebuchet MS, sans-serif']);
        DB::table('fonts')->insert(['text' => 'Arial, sans-serif', 'value' => 'Arial, sans-serif']);
        DB::table('fonts')->insert(['text' => 'Helvetica, sans-serif', 'value' => 'Helvetica, sans-serif']);
        DB::table('fonts')->insert(['text' => 'Verdana, sans-serif', 'value' => 'Verdana, sans-serif']);
        DB::table('fonts')->insert(['text' => 'Gill Sans, sans-serif', 'value' => 'Gill Sans, sans-serif']);
        DB::table('fonts')->insert(['text' => 'Times, Times New Roman, serif', 'value' => 'Times, Times New Roman, serif']);
        DB::table('fonts')->insert(['text' => 'Georgia, serif', 'value' => 'Georgia, serif']);
        DB::table('fonts')->insert(['text' => 'monospace', 'value' => 'monospace']);
        DB::table('fonts')->insert(['text' => 'Cambria', 'value' => 'Cambria']);
        DB::table('fonts')->insert(['text' => 'Comic Sans MS, Comic Sans, cursive', 'value' => 'Comic Sans MS, Comic Sans, cursive']);
        DB::table('fonts')->insert(['text' => 'Brush Script MT, Brush Script Std, cursive', 'value' => 'Brush Script MT, Brush Script Std, cursive']);
        DB::table('fonts')->insert(['text' => 'fantasy', 'value' => 'fantasy']);
        DB::table('fonts')->insert(['text' => 'Trattatello, fantasy', 'value' => 'Trattatello, fantasy']);
        DB::table('fonts')->insert(['text' => 'Lucida, sans-serif', 'value' => 'Lucida, sans-serif']);
        DB::table('fonts')->insert(['text' => 'Palatino, serif', 'value' => 'Palatino, serif']);
        DB::table('fonts')->insert(['text' => 'Bookman, serif', 'value' => 'Bookman, serif']);
        DB::table('fonts')->insert(['text' => 'New Century Schoolbook, serif', 'value' => 'New Century Schoolbook, serif']);
        DB::table('fonts')->insert(['text' => 'Lucidatypewriter, monospace', 'value' => 'Lucidatypewriter, monospace']);
        DB::table('fonts')->insert(['text' => 'Courier New, monospace', 'value' => 'Courier New, monospace']);
        DB::table('fonts')->insert(['text' => 'Impact, fantasy', 'value' => 'Impact, fantasy']);
        DB::table('fonts')->insert(['text' => 'cursive', 'value' => 'cursive']);
        DB::table('fonts')->insert(['text' => 'Garamond', 'value' => 'Garamond']);
        DB::table('fonts')->insert(['text' => 'Century Gothic', 'value' => 'Century Gothic']);
        DB::table('fonts')->insert(['text' => 'New Century Schoolbook', 'value' => 'New Century Schoolbook']);
        DB::table('fonts')->insert(['text' => 'Brush Script Std', 'value' => 'Brush Script Std']);
        DB::table('fonts')->insert(['text' => 'Copperplate', 'value' => 'Copperplate']);
        DB::table('fonts')->insert(['text' => 'papyrus', 'value' => 'papyrus']);
        DB::table('fonts')->insert(['text' => 'Bahnschrift SemiLight', 'value' => 'Bahnschrift SemiLight']);
        DB::table('fonts')->insert(['text' => 'Gabriola', 'value' => 'Gabriola']);
        DB::table('fonts')->insert(['text' => 'Lucida Handwriting', 'value' => 'Lucida Handwriting']);
        DB::table('fonts')->insert(['text' => 'Lucida Sans Typewriter', 'value' => 'Lucida Sans Typewriter']);
        DB::table('fonts')->insert(['text' => 'MS Gothic', 'value' => 'MS Gothic']);
    }
}


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permissions
        //   $permissions = [
        Permission::create(['name' => 'listrole', 'Parent' => 'Rôle', 'NumParent' => '1', 'TypeParent' => 'P1', 'Libelle' => 'Liste', 'Lien' => '<a  data-toggle="tooltip"  data-original-title="Details" class="btn btn-default btn-sm" title="Details"><span class="blue"><i class="ace-icon fa fa-eye"></i></span></a>']);
        Permission::create(['name' => 'createrole', 'Parent' => 'Rôle', 'NumParent' => '1', 'TypeParent' => 'P1', 'Libelle' => 'Créer', 'Lien' => '<a class="btn btn-sm btn-round btn-primary"  ><span class=" glyphicon glyphicon-plus"></span></a>']);
        Permission::create(['name' => 'editrole', 'Parent' => 'Rôle', 'NumParent' => '1', 'TypeParent' => 'P1', 'Libelle' => 'Modifier', 'Lien' => '<a   data-original-title="Modifier" class="btn btn-warning btn-sm"  title="Modifier"><span class="white"><i class="glyphicon glyphicon-edit"></i></span></a>']);
        Permission::create(['name' => 'deleterole', 'Parent' => 'Rôle', 'NumParent' => '1', 'TypeParent' => 'P1', 'Libelle' => 'Supprimer', 'Lien' => '<a  data-toggle="tooltip"   data-original-title="Supprimer" class="btn btn-danger btn-sm"  title="Supprimer"><span class="white"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a>']);
        Permission::create(['name' => 'listposte', 'Parent' => 'Poste', 'NumParent' => '2', 'TypeParent' => 'P2', 'Libelle' => 'Liste', 'Lien' => '<a  data-toggle="tooltip"  data-original-title="Details" class="btn btn-default btn-sm" title="Details"><span class="blue"><i class="ace-icon fa fa-eye"></i></span></a>']);
        Permission::create(['name' => 'createposte', 'Parent' => 'Poste', 'NumParent' => '2', 'TypeParent' => 'P2', 'Libelle' => 'Créer', 'Lien' => '<a class="btn btn-sm btn-round btn-primary"  ><span class=" glyphicon glyphicon-plus"></span></a>']);
        Permission::create(['name' => 'editposte', 'Parent' => 'Poste', 'NumParent' => '2', 'TypeParent' => 'P2', 'Libelle' => 'Modifier', 'Lien' => '<a   data-original-title="Modifier" class="btn btn-warning btn-sm"  title="Modifier"><span class="white"><i class="glyphicon glyphicon-edit"></i></span></a>']);
        Permission::create(['name' => 'deleteposte', 'Parent' => 'Poste', 'NumParent' => '2', 'TypeParent' => 'P2', 'Libelle' => 'Supprimer', 'Lien' => '<a  data-toggle="tooltip"   data-original-title="Supprimer" class="btn btn-danger btn-sm"  title="Supprimer"><span class="white"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a>']);
        Permission::create(['name' => 'listuser', 'Parent' => 'Utilisateur', 'NumParent' => '3', 'TypeParent' => 'P3', 'Libelle' => 'Liste', 'Lien' => '<a  data-toggle="tooltip"  data-original-title="Details" class="btn btn-default btn-sm" title="Details"><span class="blue"><i class="ace-icon fa fa-eye"></i></span></a>']);
        Permission::create(['name' => 'createuser', 'Parent' => 'Utilisateur', 'NumParent' => '3', 'TypeParent' => 'P3', 'Libelle' => 'Créer', 'Lien' => '<a class="btn btn-sm btn-round btn-primary"  ><span class=" glyphicon glyphicon-plus"></span></a>']);
        Permission::create(['name' => 'edituser', 'Parent' => 'Utilisateur', 'NumParent' => '3', 'TypeParent' => 'P3', 'Libelle' => 'Modifier', 'Lien' => '<a   data-original-title="Modifier" class="btn btn-warning btn-sm"  title="Modifier"><span class="white"><i class="glyphicon glyphicon-edit"></i></span></a>']);
        Permission::create(['name' => 'deleteuser', 'Parent' => 'Utilisateur', 'NumParent' => '3', 'TypeParent' => 'P3', 'Libelle' => 'Supprimer', 'Lien' => '<a  data-toggle="tooltip"   data-original-title="Supprimer" class="btn btn-danger btn-sm"  title="Supprimer"><span class="white"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a>']);
        Permission::create(['name' => 'listentreprise', 'Parent' => 'Entreprise', 'NumParent' => '4', 'TypeParent' => 'P4', 'Libelle' => 'Liste', 'Lien' => '<a  data-toggle="tooltip"  data-original-title="Details" class="btn btn-default btn-sm" title="Details"><span class="blue"><i class="ace-icon fa fa-eye"></i></span></a>']);
        Permission::create(['name' => 'createentreprise', 'Parent' => 'Entreprise', 'NumParent' => '4', 'TypeParent' => 'P4', 'Libelle' => 'Créer', 'Lien' => '<a class="btn btn-sm btn-round btn-primary"  ><span class=" glyphicon glyphicon-plus"></span></a>']);
        Permission::create(['name' => 'editentreprise', 'Parent' => 'Entreprise', 'NumParent' => '4', 'TypeParent' => 'P4', 'Libelle' => 'Modifier', 'Lien' => '<a   data-original-title="Modifier" class="btn btn-warning btn-sm"  title="Modifier"><span class="white"><i class="glyphicon glyphicon-edit"></i></span></a>']);
        Permission::create(['name' => 'deleteentreprise', 'Parent' => 'Entreprise', 'NumParent' => '4', 'TypeParent' => 'P4', 'Libelle' => 'Supprimer', 'Lien' => '<a  data-toggle="tooltip"   data-original-title="Supprimer" class="btn btn-danger btn-sm"  title="Supprimer"><span class="white"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a>']);
        Permission::create(['name' => 'listcompte', 'Parent' => 'Compte', 'NumParent' => '9', 'TypeParent' => 'P9', 'Libelle' => 'Liste', 'Lien' => '<a  data-toggle="tooltip"  data-original-title="Details" class="btn btn-default btn-sm" title="Details"><span class="blue"><i class="ace-icon fa fa-eye"></i></span></a>']);
        Permission::create(['name' => 'createcompte', 'Parent' => 'Compte', 'NumParent' => '9', 'TypeParent' => 'P9', 'Libelle' => 'Créer', 'Lien' => '<a class="btn btn-sm btn-round btn-primary"  ><span class=" glyphicon glyphicon-plus"></span></a>']);
        Permission::create(['name' => 'editcompte', 'Parent' => 'Compte', 'NumParent' => '9', 'TypeParent' => 'P9', 'Libelle' => 'Modifier', 'Lien' => '<a   data-original-title="Modifier" class="btn btn-warning btn-sm"  title="Modifier"><span class="white"><i class="glyphicon glyphicon-edit"></i></span></a>']);
        Permission::create(['name' => 'deletecompte', 'Parent' => 'Compte', 'NumParent' => '9', 'TypeParent' => 'P9', 'Libelle' => 'Supprimer', 'Lien' => '<a  data-toggle="tooltip"   data-original-title="Supprimer" class="btn btn-danger btn-sm"  title="Supprimer"><span class="white"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a>']);
        

        Permission::create(['name' => 'listparam', 'Parent' => 'Paramètres', 'NumParent' => '17', 'TypeParent' => 'P17', 'Libelle' => 'Détails', 'Lien' => '<a  data-toggle="tooltip"  data-original-title="Details" class="btn btn-default btn-sm" title="Details"><span class="blue"><i class="ace-icon fa fa-eye"></i></span></a>']);
        Permission::create(['name' => 'editparam', 'Parent' => 'Paramètres', 'NumParent' => '17', 'TypeParent' => 'P17', 'Libelle' => 'Modifier', 'Lien' => '<a   data-original-title="Modifier" class="btn btn-warning btn-sm"  title="Modifier"><span class="white"><i class="glyphicon glyphicon-edit"></i></span></a>']);


    }
}

class SexeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sexes')->insert([
            'text' => 'Masculin',
            'value' => 'M',
        ]);

        DB::table('sexes')->insert([
            'text' => 'Féminin',
            'value' => 'F',
        ]);
    }
}



class EntrepriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('entreprises')->truncate();
        //Creation entreprise
        DB::table('entreprises')->insert([
            'id' => '1',
            'Taille' => '14',
            'Police' => 'Cambria',
            'ColorEntete' => 'rgb(3, 39, 60)',
            'ColorSidebar' => '',
            'LogoEntreprise' => '',
            'EmailNotification' => '',
            'PasswordNotification' => '',
            'Nom' => 'NORA SHOP',
            'NomReduit' => 'NORA SHOP',
            'Email' => 'arsemeglo@gmail.com',
            'Telephone' => '+22891207494',
            'Adresse' => '',
        ]);

        DB::table('entreprises')->insert([
            'id' => '2',
            'Taille' => '14',
            'Police' => 'Cambria',
            'ColorEntete' => 'rgb(3, 39, 60)',
            'ColorSidebar' => '',
            'LogoEntreprise' => '',
            'EmailNotification' => '',
            'PasswordNotification' => '',
            'Nom' => 'Basile & fils',
            'NomReduit' => 'Basile & Fils',
            'Email' => 'basile14sowadan@gmail.com',
            'Telephone' => '+22890499511',
            'Adresse' => '',
        ]);
    }
}


class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('comptes')->truncate();
        //Creation compte
        DB::table('comptes')->insert([
            'Montant' => '0', //Problem ici
            'Libelle' => 'Caisse principale',
            // 'DateCreation'=>Carbon::now(),
            'EntrepriseId' => '1',
            'TypeCompteId' => '1',
            // 'SaveNumber'=>'1',
            // 'Anne/xeID'=>'1',
        ]);
    }
}
class TypeCompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('Type_Comptes')->truncate();
        //Creation compte
        DB::table('Type_Comptes')->insert([
           
            'Libelle' => 'Epargne',
            // 'DateCreation'=>Carbon::now(),
            'EntrepriseId' => '1',
            // 'SaveNumber'=>'1',
            // 'Anne/xeID'=>'1',
        ]);
    }
}


class PosteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('posts')->truncate();
        //Creation des users
        DB::table('posts')->insert([
            'Libelle' => 'Directeur général',
            // 'DateCreation'=>Carbon::now(),
            'EntrepriseId' => '1',
        ]);
        DB::table('posts')->insert([
            'Libelle' => 'Directeur Adjoint',
            // 'DateCreation'=>Carbon::now(),
            'EntrepriseId' => '2',
        ]);
    }
}

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->truncate();
        //Creation des users
        $user = User::create([
            'AdherentId' => '1',
            'Email' => 'arsemeglo@gmail.com',
            'SuperAdmin' => '1',
            'password' => Hash::make('123456789'),
            'EntrepriseId' => '1',
            'ImageUser' => '',
        ]);

        DB::table('users')->insert([
            'AdherentId' => '1',
            'Email' => 'basile14sowadan@gmail.com',
            'SuperAdmin' => '1',
            'password' => Hash::make('123456789'),          
            'EntrepriseId' => '1',          
            'ImageUser' => '',
        ]);

        $role = Role::create(['Nom' => 'Admin', 'EntrepriseId' => '1']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}

