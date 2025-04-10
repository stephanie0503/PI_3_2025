<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAccount extends Model
{
    use HasFactory;

    // tabela associada ao modelo
    protected $table = 'email_accounts';

    // atributos que podem ser preenchidos em massa
    protected $fillable = [
        'user_id',
        'email',
        'smtp_host',
        'smtp_port',
        'smtp_user',
        'smtp_password',
        'imap_host',
        'imap_port',
        'imap_user',
        'imap_password',
    ];

    // criptografar as senhas
    protected $hidden = [
        'smtp_password',
        'imap_password',
    ];

    // Defina a relacao com o model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
