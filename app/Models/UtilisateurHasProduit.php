<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilisateurHasProduit extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'produit_id',
        'action',
        'quantite',
    ];
}