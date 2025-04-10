<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relacao com o modelo User (vendor)
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Relacao com o modelo Client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(ProposalItem::class, 'proposal_id');
    }
}
