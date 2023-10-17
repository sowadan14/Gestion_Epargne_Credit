<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;
    protected $fillable = [
        'Email',
        'Code',
        'id',
        'Telephone',
        'Adresse',
        'Taille',
        'Police',
        'Devise',
        'ColorEntete',
        'ColorSidebar',
        'ColorFont',
        'id',
        'LogoEntreprise',
        
        'EmailNotification',
        'PasswordNotification',
        'Nom',
        'TVA',
        'Remise',
        'NomReduit',
        'Supp_util',
        'Modif_util',
        'Create_user',
        'Edit_user',
        'Delete_user',
        'Supprimer',
    ];
    
}
