<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailContrat extends Model
{
    use HasFactory;

    protected $fillable = [
        'AdherentId',
        'ContratId',
       'Modalite',
       'Montant',
       'DateDebut',
       'DateFin',
       'Entrepriseid',
    ];
}
