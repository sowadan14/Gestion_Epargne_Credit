<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adherent extends Model
{
    use HasFactory;

    protected $fillable = [
        'Nom',
        'Prenom',
        'Ville',
        'Status',
        'Pays',
        'DateAdhesion',
        'Datenaissance',
        'Profession',
        'Email',
        'id',
        'Telephone',
        'Adresse',
        'EntrepriseId',
      
];

}
