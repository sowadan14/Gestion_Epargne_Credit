<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recouvrement extends Model
{
    use HasFactory;

    protected $fillable = [
        'CompteId',
        'EntrepriseId',
       'Date',
       'Montant',
       'Libelle',
       'Modalite',
    ];
}
