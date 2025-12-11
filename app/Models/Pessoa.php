<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    public const TIPO_FISICA = 'fisica';
    public const TIPO_JURIDICA = 'juridica';

    protected $table = 'pessoas';

    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'tipo',
        'telefone',
        'email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
