<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocacionRequest;
use App\Http\Requests\UpdateLocacionRequest;
use App\Models\Locacion;
use App\Repositorios\LocacionRepository;
use Illuminate\Http\Request;

class LocacionController extends Controller
{
    private Locacion $locacion;

    public  function __construct(Locacion $locacion)
    {
        $this->locacion = $locacion;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $locacionRepository = new LocacionRepository($this->locacion);
        $locacions = array();

        if($request->has('atributos_cliente')){ //atributos_carro=nombre,imagen

            $atributos_cliente= 'cliente:id,'.$request->atributos_cliente;
            $locacionRepository->selectAtibutosRelacionados($atributos_cliente);

        }else{
            $locacionRepository->selectAtibutosRelacionados('cliente');
        }

        if($request->has('atributos_carro')){ //atributos_carro=nombre,imagen

            $atributos_carro= 'carro:id,'.$request->atributos_carro;
            $locacionRepository->selectAtibutosRelacionados($atributos_carro);

        }else{
            $locacionRepository->selectAtibutosRelacionados('carro');
        }

        if($request->has('filtro')){ //filtro=numero_puertas:>:4;nombre:like:Ford%

            $locacionRepository->filtro($request->filtro);

        }

        if($request->has('atributos')){ //atributos=id,nombre,lugares,numero_puertas

            $atributos= $request->atributos.',carro_id,cliente_id';
            $locacionRepository->selectAtibutos($atributos);

        }
        $locacions = $locacionRepository->getResultado();
        return response()->json($locacions,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->locacion->rules());
        $locacion = $this->locacion->create($request->all());
        return response()->json($locacion,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $locacion = $this->locacion->find($id);
        if($locacion=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }
        return response()->json($locacion,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $locacion = $this->locacion->find($id);
        if($locacion=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }

        if($request->method()==='PATCH'){
            $reglasDinamicas = [];
            foreach($locacion->rules() as $input => $regla){
                if(array_key_exists($input,$request->all())){
                    $reglasDinamicas[$input] = $regla;
                }
            }

            $request->validate($reglasDinamicas);

        }else{

            $request->validate($locacion->rules());
        }

        $locacion->fill($request->all());
        $locacion->save();

        return response()->json($locacion,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $locacion = $this->locacion->find($id);

        if($locacion=== null){
            return ['msj'=>'No existe el recurso buscado'];
        }

        $locacion->delete();
        return response()->json(['msj'=>'Locacion eliminada correctamente'],200);
    }
}
