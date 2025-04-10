<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClientService;
use Illuminate\Validation\ValidationException;
use Exception;

class ClientController extends Controller
{



    public function __construct
    (
        protected ClientService $clientService
    ) {
    }

    public function index(Request $request)
    {

        $name = $request->query('name');

        if ($name) {
            $clientsList = $this->clientService->getClientsByName($name);
        } else {
            $clientsList = $this->clientService->getClients();


        }

        return response()->json($clientsList);

    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            
            $store = $this->clientService->store($request->all());

            if($store) {
                return response()->json([
                    'message' => 'Cliente criado com sucesso',
                    'data' => $store
                ], 201); // HTTP 201 - Created
            }
            
        } catch (ValidationException $e) {

            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $e->getMessage() 
            ], 422);
        } catch (\Exception $e) {
            //
            return response()->json([
                'error' => 'Ocorreu um erro ao criar o cliente', 
            ], 500);
        }
    }




    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy($id)
    {
        try {
            $result = $this->clientService->destroy($id);

            return response()->json($result, 200); 
        } catch (Exception $e) {
    
            $statusCode = $e->getCode() ?: 500;

            return response()->json([
                'error' => 'Erro ao deletar cliente.',
            ], $statusCode); 
        }
    }
    
}
