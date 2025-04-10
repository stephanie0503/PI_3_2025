<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use App\Models\Client;
class ClientService
{
    public function getClients()
    {
        return Client::all();
    }

    public function getClientById($id)
    {
        try {
            return Client::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }



    public function getClientsCount()
    {
        return Client::count();
    }

    public function getClientsByName($name)
    {
        $name = strtolower($name);

        return Client::select('id', 'name', 'fantasy_name', 'email', 'zip_code')
            ->whereRaw('LOWER(fantasy_name) LIKE ?', ['%' . $name . '%'])
            ->orWhereRaw('LOWER(email) LIKE ?', ['%' . $name . '%'])
            ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $name . '%'])
            ->get();
    }


    public function store(array $data)
    {
        try {

            $validator = Validator::make(
                $data,
                [
                    'clientName' => 'required|string|max:255',
                    'clientFantasyName' => 'nullable|string|max:255',
                    'clientEmail' => 'required|email|unique:clients,email',
                    'cnpj' => 'required|string|size:14|unique:clients,cnpj',
                    'address' => 'required|string|max:255',
                    'zipCode' => 'required|string|size:8',
                    'neighborhood' => 'required|string|max:255',
                    'city' => 'required|string|max:255',
                    'state' => 'required|string|max:2',
                    'phone' => 'nullable|string|max:15',
                    'mobile' => 'nullable|string|max:15',
                ]
                ,
                [
                    'clientName.required' => 'O nome do cliente é obrigatório.',
                    'clientEmail.required' => 'O e-mail do cliente é obrigatório.',
                    'clientEmail.email' => 'Por favor, forneça um e-mail válido.',
                    'clientEmail.unique' => 'Este e-mail já está cadastrado.',
                    'cnpj.required' => 'O CNPJ é obrigatório.',
                    'cnpj.unique' => 'Já existe um cadastro com esse CNPJ.',
                    'cnpj.size' => 'O CNPJ precisa ter 14 caracteres.',
                ]
            );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $client = Client::create([
                'name' => $data['clientName'],
                'fantasy_name' => $data['clientFantasyName'],
                'email' => $data['clientEmail'],
                'cnpj' => $data['cnpj'],
                'address' => $data['address'],
                'number' => $data['addressNumber'] ?? null,
                'zip_code' => $data['zipCode'],
                'neighborhood' => $data['neighborhood'],
                'city' => $data['city'],
                'state' => $data['state'],
                'ccTelCli' => $data['phone'],
                'ccCelCli' => $data['mobile'],
                'contact_name' => $data['contact'] ?? null,
            ]);

            return $client;

        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                // Re-lança a mesma ValidationException
                throw $e;
            }
            // Caso contrário, lança a exceção genérica
            throw new Exception("Erro ao criar cliente: " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);

            $client->delete();

            return [
                'message' => 'Cliente excluído com sucesso.'
            ];
        } catch (ModelNotFoundException $e) {
            throw new Exception('Cliente não encontrado.', 404);
        } catch (Exception $e) {
            throw new Exception('Erro ao excluir o cliente: ' . $e->getMessage(), 500);
        }
    }


}

