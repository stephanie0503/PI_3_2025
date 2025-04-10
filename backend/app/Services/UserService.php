<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistered;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUsersByName($name)
    {
        return User::select('id', 'name', 'email')
            ->where('name', 'like', '%' . $name . '%')
            ->get();
    }

    public function getUserByEmail($email) {
        return User::where('email', $email)->first(); 
    }
    
    


    public function createUser($data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verification_token' => Str::random(60),
        ]);

        // Enviar e-mail de registro
        Mail::to($user->email)->send(new UserRegistered($user));

        return $user;
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function updateUser($id, $data)
    {
        $user = User::findOrFail($id);

        // Verifica se a 'profilepicture' esta presente
        if (isset($data['profilepicture']) && $data['profilepicture']) {
            if (filter_var($data['profilepicture'], FILTER_VALIDATE_URL)) {
                // Se for uma URL, mantem o valor existente no banco de dados
                // Portanto, nao faz mudancas no campo 'profilepicture'
                $profilePicturePath = $user->profilepicture;
            } else {
                // Se for base64, processa a imagem
                $profilePicturePath = $this->processImage($data['profilepicture']);
            }

            // Define o caminho da imagem processada ou a URL existente
            $data['profilepicture'] = $profilePicturePath;
        } else {
            // Se 'profilepicture' nao estiver presente ou estiver vazia, insere vazio
            $data['profilepicture'] = "";
        }

        // Atualiza os dados do usuario
        $user->update([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => $data['status'],
            'role' => isset($data['role']) ? $data['role'] : $user->role,
            'password' => isset($data['password']) ? bcrypt($data['password']) : $user->password,
            'profilepicture' => $data['profilepicture']
        ]);

        return response()->json($user);
    }


    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $user;
    }



    protected function processImage($base64Image)
    {
        // Extrai o conteúdo da imagem base64
        $imageData = explode(',', $base64Image);
        $imageType = str_replace('data:image/', '', $imageData[0]);
        $imageType = str_replace(';base64', '', $imageType);
        $imageContent = base64_decode($imageData[1]);

        // Calcula o hash MD5 do conteúdo da imagem para verificar duplicatas
        $imageHash = md5($imageContent);

        // Define o caminho onde as imagens serão salvas
        $storagePath = public_path('storage/profile_pictures/');

        // Cria a pasta caso não exista
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Verifica se a imagem já existe
        $existingImagePath = $this->getImagePathByHash($imageHash, $imageType, $storagePath);
        if ($existingImagePath) {
            // Se a imagem já existe, retorna o caminho existente
            return $existingImagePath;
        }

        // Gera um nome único para a imagem
        $imageName = $imageHash . '.' . $imageType;
        $imagePath = $storagePath . $imageName;

        // Salva a imagem no disco
        file_put_contents($imagePath, $imageContent);

        // Retorna o caminho da imagem
        return 'storage/profile_pictures/' . $imageName;
    }

    protected function getImagePathByHash($hash, $imageType, $storagePath)
    {
        // Procura um arquivo no diretório com o hash como nome
        $imageName = $hash . '.' . $imageType;
        $imagePath = $storagePath . $imageName;

        // Verifica se o arquivo existe
        if (file_exists($imagePath)) {
            return 'storage/profile_pictures/' . $imageName;
        }

        return null;
    }

}
