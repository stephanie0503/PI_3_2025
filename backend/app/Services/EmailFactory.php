<?php

namespace App\Services;

use App\Mail\ProposalSignatureEmail;
use Illuminate\Support\Facades\Mail;

class EmailFactory
{
    
    public static function sendProposalSignatureEmail($email, $link)
    {
        Mail::to($email)->send(new ProposalSignatureEmail($link));
    }

}
