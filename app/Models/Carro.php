<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Carro extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable = ['modelo_id','placa','disponible','km'];

    public function rules(){

        return [
            'modelo_id'=>'exists:modelos,id',
            'placa'=>'required|unique:carros,placa,'.$this->id,
            'disponible'=>'required',
            'km' => 'required|integer'
        ];
    }

    public function modelo():BelongsTo{
        return $this->belongsTo(Modelo::class);
    }
}
