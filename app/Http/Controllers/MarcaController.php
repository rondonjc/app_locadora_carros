<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositorios\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\Console\Input\Input;

class MarcaController extends Controller
{
    private Marca $marca;

    public  function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $marcaRepository = new MarcaRepository($this->marca);
        $marcas = array();

        if($request->has('atributos_modelos')){ //atributos_marca=nombre,imagen

            $atributos_modelos= 'modelos:marca_id,'.$request->atributos_modelos;
            $marcaRepository->selectAtibutosRelacionados($atributos_modelos);

        }else{
            $marcaRepository->selectAtibutosRelacionados('modelos');
        }

        if($request->has('filtro')){ //filtro=numero_puertas:>:4;nombre:like:Ford%

            $marcaRepository->filtro($request->filtro);

        }

        if($request->has('atributos')){ //atributos=id,nombre,lugares,numero_puertas

            $atributos= $request->atributos.',id';
            $marcaRepository->selectAtibutos($atributos);

        }
        $marcas = $marcaRepository->getResultadoPaginado();
        return response()->json($marcas,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(),$this->marca->feedback());

        $imagen = $request->file('imagen');
        $imagenUrl = $imagen->store('imagens','public');

        $marca = $this->marca->create([
            'nombre'=>$request->get('nombre'),
            'imagen'=>$imagenUrl
        ]);
        return response()->json($marca,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if($marca=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }
        return response()->json($marca,200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $marca = $this->marca->find($id);
        if($marca=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }

        if($request->method()==='PATCH'){
            $reglasDinamicas = [];
            foreach($marca->rules() as $input => $regla){
                if(array_key_exists($input,$request->all())){
                    $reglasDinamicas[$input] = $regla;
                }
            }

            $request->validate($reglasDinamicas,$marca->feedback());

        }else{

            $request->validate($marca->rules(),$marca->feedback());
        }

        $marca->fill($request->all());

        if($request->file('imagen')){
            Storage::disk('public')->delete($marca->imagen);
            $imagen = $request->file('imagen');
            $imagenUrl = $imagen->store('imagens','public');
            $marca->imagen = $imagenUrl;

        }

        $marca->save();

        return response()->json($marca,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $marca = $this->marca->find($id);

        if($marca=== null){
            return ['msj'=>'No existe el recurso buscado'];
        }

        Storage::disk('public')->delete($marca->imagen);

        $marca->delete();
        return response()->json(['msj'=>'Marca Eliminada correctamente'],200);
    }


    /* Web Funciones*/

    public function indexweb(){

        return view('app.marcas');
    }
}
