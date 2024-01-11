<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositorios\ModeloRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    private Modelo $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $modeloRepository = new ModeloRepository($this->modelo);
        $modelos = array();

        if($request->has('atributos_marca')){ //atributos_marca=nombre,imagen

            $atributos_marca = 'marca:id,'.$request->atributos_marca;
            $modeloRepository->selectAtibutosRelacionados($atributos_marca);

        }else{
            $modeloRepository->selectAtibutosRelacionados('marca');
        }

        if($request->has('filtro')){ //filtro=numero_puertas:>:4;nombre:like:Ford%

            $modeloRepository->filtro($request->filtro);

        }

        if($request->has('atributos')){ //atributos=id,nombre,lugares,numero_puertas

            $atributos= $request->atributos.',marca_id';
            $modeloRepository->selectAtibutos($atributos);

        }

        $modelos = $modeloRepository->getResultado();
        return response()->json($modelos,200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());

        $imagen = $request->file('imagen');
        $imagenUrl = $imagen->store('imagens/modelos','public');

        $modelo = $this->modelo->create([
                'marca_id' => $request->get('marca_id'),
                'nombre' => $request->get('nombre'),
                'imagen' => $imagenUrl,
                'numero_puertas' => $request->get('numero_puertas'),
                'lugares' => $request->get('lugares'),
                'air_bag' => $request->get('air_bag'),
                'abs' => $request->get('abs')
            ]);
        return response()->json($modelo,201);

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }
        return response()->json($modelo,200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }

        if($request->method()==='PATCH'){
            $reglasDinamicas = [];
            foreach($modelo->rules() as $input => $regla){
                if(array_key_exists($input,$request->all())){
                    $reglasDinamicas[$input] = $regla;
                }
            }
            $request->validate($reglasDinamicas);

        }else{
            $request->validate($modelo->rules());
        }

        $modelo->fill($request->all());

        if($request->file('imagen')){

            Storage::disk('public')->delete($modelo->imagen);

            $imagen = $request->file('imagen');
            $imagenUrl = $imagen->store('imagens/modelos','public');
            $modelo->imagen = $imagenUrl;
        }

        $modelo->save();

        return response()->json($modelo,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo=== null){
            return ['msj'=>'No existe el recurso buscado'];
        }

        Storage::disk('public')->delete($modelo->imagen);

        $modelo->delete();
        return response()->json(['msj'=>'Modelo Eliminado correctamente'],200);
    }
}
