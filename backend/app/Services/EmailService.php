<?php

namespace App\Services;

use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Storage;

class EmailService
{
    private $client;

    public function __construct(array $config)
    {

        $this->client = Client::make($config);
        $this->client->connect();
    }

    public function getInboxMessages()
    {
        $folder = $this->client->getFolder('INBOX');

        return $folder->messages()->all()->get();
        //return $folder->query()->all()->setFetchOrder("desc")->get();
    }

    public function prepareEmailData($messages)
    {
        $emailData = [];

        foreach ($messages as $message) {
            // iconv_mime_decode para converter caracteres especiais para UTF-8
            $subjectRaw = $message->getSubject();
            $subjectRaw = preg_replace('/[^\x20-\x7E]/', '', $subjectRaw); // Remove caracteres nao imprimiveis
            $subject = iconv_mime_decode($subjectRaw, 0, "UTF-8");

            ;
            $from = $message->getFrom();
            $attributes = $message->getAttributes();
            $body = $message->getBodies();
            $attachments = $message->getAttachments();
            $flags = $message->flags;

            $date = isset($attributes['date']) ? $attributes['date'] : '';
            $attachmentData = $this->processAttachments($attachments);

            $emailData[] = [
                'subject' => $subject ? (string) $subject : '',
                'from' => $from ? (string) $from : '',
                'date' => (string) $date,
                'attachments' => $attachmentData, //
                'body' => $body ? (array) $body : '',
                'flags' => $flags
            ];
        }

        // Ordena os emails pela data em ordem decrescente
        usort($emailData, function ($a, $b) {
            // Coloca emails sem data no final
            if ($a['date'] === null)
                return 1;
            if ($b['date'] === null)
                return -1;
            return strtotime($b['date']) <=> strtotime($a['date']);
        });

        return $emailData;
    }


    public function processAttachments($attachments)
    {
        $attachmentData = [];

        foreach ($attachments as $attachment) {
            $filename = $attachment->name;
            $fileContent = $attachment->content;

            // Verifica se o conteúdo do arquivo é válido
            if ($fileContent === null) {
                continue;
            }

            $filePath = 'public/emails/' . $filename;

            // Verifica se o arquivo já existe
            if (!Storage::disk('local')->exists($filePath)) {
                // Salva o arquivo se não existir
                Storage::disk('local')->put($filePath, $fileContent);
            }

            $attachmentData[] = [
                'filename' => $filename,
                'url' => Storage::url($filePath)
            ];
        }

        return $attachmentData;
    }


}
