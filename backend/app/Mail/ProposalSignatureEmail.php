<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalSignatureEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $link; 

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function build()
    {
        return $this->view('emails.proposal_signature')
                    ->subject('Proposta para assinar digitalmente')
                    ->with([
                        'link' => $this->link,
                    ]);
    }
}

