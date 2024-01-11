<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use App\Repositorios\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    private Cliente $cliente;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);
        $clientes = array();


        if($request->has('filtro')){ //filtro=numero_puertas:>:4;nombre:like:Ford%

            $clienteRepository->filtro($request->filtro);

        }

        if($request->has('atributos')){ //atributos=id,nombre,lugares,numero_puertas

            $atributos= $request->atributos.',id';
            $clienteRepository->selectAtibutos($atributos);

        }

        $clientes = $clienteRepository->getResultado();
        return response()->json($clientes,200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules());
        $cliente = $this->cliente->create($request->all());
        return response()->json($cliente,201);

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }
        return response()->json($cliente,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente=== null){
            return response()->json(['msj'=>'No existe el recurso buscado'],404);
        }

        if($request->method()==='PATCH'){
            $reglasDinamicas = [];
            foreach($cliente->rules() as $input => $regla){
                if(array_key_exists($input,$request->all())){
                    $reglasDinamicas[$input] = $regla;
                }
            }
            $request->validate($reglasDinamicas);

        }else{
            $request->validate($cliente->rules());
        }

        $cliente->fill($request->all());
        $cliente->save();

        return response()->json($cliente,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente=== null){
            return ['msj'=>'No existe el recurso buscado'];
        }

        $cliente->delete();
        return response()->json(['msj'=>'Cliente eliminado correctamente'],200);
    }
}
