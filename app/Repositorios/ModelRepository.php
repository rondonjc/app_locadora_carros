<?php
namespace App\Repositorios;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class ModelRepository{

    private Model $model;
    private Builder $buidl;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->buidl = $this->model->newQuery();
    }

    public function selectAtibutosRelacionados(string $atributos){

        $this->buidl->with($atributos);

    }

    public function filtro(string $filtros){

        $filtros = explode(";",$filtros);
        foreach($filtros as $filtro){
            $condicion = explode(":",$filtro);
            $this->buidl->where($condicion[0],$condicion[1],$condicion[2]);
        }
    }


    public function selectAtibutos(string $atributos){

        $this->buidl->selectRaw($atributos);

    }

    public function getResultado():Collection{
        return $this->buidl->get();
    }

    public function getResultadoPaginado():LengthAwarePaginator{
        return $this->buidl->paginate(3);
    }
}

?>
