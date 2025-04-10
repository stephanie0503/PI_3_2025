<?php

namespace App\Services;

use App\Models\EmailAccount;
use Illuminate\Support\Facades\Crypt;

class WebmailService
{
    /**
     * Atualiza ou cria uma conta de email para um user
     *
     * @param int $userId
     * @param array $webmailData
     * @return void
     */
    public function updateOrCreateEmailAccount($userId, array $webmailData)
    {
        try {
            EmailAccount::updateOrCreate(
                ['user_id' => $userId],
                [
                    'smtp_host' => $webmailData['smtp_host'] ?? null,
                    'smtp_port' => $webmailData['smtp_port'] ?? null,
                    'smtp_user' => $webmailData['smtp_user'] ?? null,
                    'smtp_password' => isset($webmailData['smtp_password']) ? Crypt::encryptString($webmailData['smtp_password']) : null,
                    'imap_host' => $webmailData['imap_host'] ?? null,
                    'imap_port' => $webmailData['imap_port'] ?? null,
                    'imap_user' => $webmailData['imap_user'] ?? null,
                    'imap_password' => isset($webmailData['imap_password']) ? Crypt::encryptString($webmailData['imap_password']) : null,
                ]
            );
        } catch (\Exception $e) {
            //return response()->json($e);
        }
    }


}
