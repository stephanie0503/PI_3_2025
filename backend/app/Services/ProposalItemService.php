<?php

namespace App\Services;

use App\Models\ProposalItem;


class ProposalItemService
{
    public function storeProposalItems($proposalId, $items)
    {
        try {
            foreach ($items as $item) {
                ProposalItem::create([
                    'proposal_id' => $proposalId,   
                    'product_code' => $item['code'] ?? 0,   
                    'description' => $item['description'],
                    'quantity' => $item['quantity'], 
                    'unit_price' => $item['unitPrice'], 
                ]);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error while saving proposal items: ' . $e->getMessage());
        }
    }



}