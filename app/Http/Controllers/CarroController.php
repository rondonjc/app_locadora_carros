<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Models\Carro;
use App\Repositorios\CarroRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarroController extends Controller
{
    private Carro $carro;

    public  function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $carroRepository = new CarroRepository($this->carro);
        $carros = array();

        if($request->has('atributos_modelo')){ //atributos_carro=nombre,imagen

            $atributos_modelo= 'modelo:id,'.$request->atributos_modelo;
            $carroRepository->selectAtibutosRelacionados($atributos_modelo);

        }else{
            $carroRepository->selectAtibutosRelacionados('modelo');
        }

        if($request->has('filtro')){ //filtro=numero_puertas:>:4;nombre:like:Ford%

            $carroRepository->filtro($request->filtro);

        }

        if($request->has('atributos')){ //atributos=id,nombre,lugares,numero_puertas

            $atributos= $request->atributos.',modelo_id';
            $carroRepository->selectAtibutos($atributos);

        }
        $carros = $carroRepository->getResultado();
        return response()->json($carros,200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules());
        $carro = $this->carro->create($request->all());
        return response()->json($carro,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if($carro=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }
        return response()->json($carro,200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $carro = $this->carro->find($id);
        if($carro=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }

        if($request->method()==='PATCH'){
            $reglasDinamicas = [];
            foreach($carro->rules() as $input => $regla){
                if(array_key_exists($input,$request->all())){
                    $reglasDinamicas[$input] = $regla;
                }
            }

            $request->validate($reglasDinamicas);

        }else{

            $request->validate($carro->rules());
        }

        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $carro = $this->carro->find($id);

        if($carro=== null){
            return ['msj'=>'No existe el recurso buscado'];
        }

        $carro->delete();
        return response()->json(['msj'=>'Carro eliminado correctamente'],200);
    }
}
