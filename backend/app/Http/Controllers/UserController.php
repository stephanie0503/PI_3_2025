<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\WebmailService;


class UserController extends Controller
{

    public function __construct
    (
        protected UserService $userService, 
        protected WebmailService $webMailService 
    ) {}

    // Lista todos os usuarios
    public function index(Request $request)
    {
        $name = $request->query('name');

        if ($name) {
            $users = $this->userService->getUsersByName($name);
        } else {
            $users = $this->userService->getAllUsers();
        }

        return response()->json($users);
    }


    // Registra um novo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->userService->createUser($request->all());
        return response()->json(['user' => $user]);
    }

    // Obtem os dados de um usuario pelo ID
    public function show($id)
    {
        try {
            $user = $this->userService->getUserById($id);

            $user->profilepicture = url('storage/profile_pictures/' . basename($user->profilepicture));
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // Atualiza um usuario existente

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'status' => 'required|string|in:active,inactive,pending',
                'password' => 'nullable|string|min:6',
                'profilepicture' => 'nullable|string',
                'webmail' => 'nullable|array',
            ]);

            $this->userService->updateUser($id, $validatedData);

            if (isset($validatedData['webmail']) && is_array($validatedData['webmail'])) {
                $this->webMailService->updateOrCreateEmailAccount($id, $validatedData['webmail']);
            }

            return response()->json(['message' => 'User updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => "error updating user"], 500);
        }
    }




    // Remove um usuario
    public function delete($id)
    {
        $user = $this->userService->deleteUser($id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}
