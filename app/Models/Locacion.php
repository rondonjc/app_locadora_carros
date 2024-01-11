<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Locacion extends Model
{
    use HasFactory;
    protected $table = 'locaciones';
    protected $fillable = [
        'cliente_id',
        'carro_id',
        'fecha_inicio_periodo',
        'fecha_final_previsto_periodo',
        'fecha_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final'
    ];

    public function rules(){
        return [
            'cliente_id'=>'exists:clientes,id',
            'carro_id'=>'exists:carros,id',
            'fecha_inicio_periodo'=>'required',
            'fecha_final_previsto_periodo'=>'required',
            'fecha_final_realizado_periodo'=>'required',
            'valor_diaria' => 'required',
            'km_inicial' => 'required|integer',
            'km_final' => 'required|integer',
        ];
    }

    public function cliente():BelongsTo{

        return $this->belongsTo(Cliente::class);

    }

    public function carro():BelongsTo{

        return $this->belongsTo(Carro::class);

    }

}
