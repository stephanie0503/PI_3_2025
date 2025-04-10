<?php

namespace App\Http\Controllers;

use App\Services\EmailService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\WebEmailService;
use App\Models\EmailAccount;
use Illuminate\Support\Facades\Crypt;


class EmailController extends Controller
{

    public function index(Request $request)
    {

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Buscar dados de e-mail do usuario
        $emailAccount = EmailAccount::where('user_id', $user->id)->first();

        if (!$emailAccount) {
            return response()->json(['error' => 'Email account not found.'], 404);
        }

        // config com os dados do user
        $config = [
            'host' => $request->input('host', $emailAccount->imap_host),
            'port' => $request->input('port', $emailAccount->imap_port),
            'protocol' => $request->input('protocol', 'imap'),
            'encryption' => $request->input('encryption', 'ssl'),
            'validate_cert' => $request->input('validate_cert', true),
            'username' => $request->input('username', $emailAccount->imap_user),
            'password' => $request->input('password', Crypt::decryptString($emailAccount->imap_password)),
        ];

        $emailService = new EmailService($config);

        $messages = $emailService->getInboxMessages();

        $emailData = $emailService->prepareEmailData($messages);

        return response()->json($emailData);
    }


}

