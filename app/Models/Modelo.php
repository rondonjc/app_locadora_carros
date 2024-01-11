<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modelo extends Model
{
    use HasFactory;
    protected $fillable = ['marca_id','nombre','imagen','numero_puertas','lugares','air_bag','abs'];

    public function rules(){

        return [
            'marca_id'=>'exists:marcas,id',
            'nombre'=>'required|unique:marcas,nombre,'.$this->id.'|min:3',
            'imagen'=>'required|file|mimes:png,jpeg,jpg',
            'numero_puertas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag'=>'required|boolean',
            'abs'=>'required|boolean',
        ];
    }

    public function marca():BelongsTo{

        return $this->belongsTo(Marca::class);

    }
}
