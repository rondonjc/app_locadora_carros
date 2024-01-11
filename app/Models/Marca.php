<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nombre','imagen'];

    public function rules(){

        return [
            'nombre'=>'required|unique:marcas,nombre,'.$this->id.'|min:3',
            'imagen'=>'required|file|mimes:png,jpg'
        ];
    }

    public function feedback(){

        return [
            'required'=>'El campo :attribute es requerido',
            'nombre.unique'=>'La marca ya esta registrada',
            'nombre.min'=>'La marca debe tener minimo 3 caracteres',
            'imagen.file'=>'La imagen tiene que se un archivo',
            'imagen.mimes' => 'la imagen tiene que ser de tipo PNG'
        ];
    }

    public function modelos():HasMany
    {
        return $this->hasMany(Modelo::class);
    }
}
