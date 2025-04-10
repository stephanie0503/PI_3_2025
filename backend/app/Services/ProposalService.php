<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Proposal;
use Illuminate\Support\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Ramsey\Uuid\Guid\Guid;

class ProposalService
{

    public function __construct
    (
        protected ClientService $clientService,
        protected UserService $userService,
        protected ProposalItemService $proposalItemService
    ) {}

    public function getAllProposals()
    {
        return Proposal::with(['vendor:id,name', 'client:id,name,fantasy_name'])
            ->select('id', 'client_id', 'vendor_id', 'vendor_name', 'total_value', 'created_at')
            ->orderBy('created_at', 'DESC')
            ->get();
    }


    public function storeProposal($data)
    {
        DB::beginTransaction();

        try {
            $client = $this->clientService->getClientById($data['clientData']['clientId']);
            $vendor = $this->userService->getUserByEmail($data['clientData']['consultantEmail']);

            if (!$client) {
                return [
                    'success' => false,
                    'message' => 'CLIENT_NOT_FOUND'
                ];
            }

            if (!$vendor) {
                return [
                    'success' => false,
                    'message' => 'USER_VENDOR_NOT_FOUND'
                ];
            }

            // Criar a proposta
            $proposal = Proposal::create([
                'vendor_name' => $data['clientData']['consultantName'],
                'client_id' => $client->id,
                'vendor_id' => $vendor->id,

                'total_value' => $data['formData']['total'],
                'payment_method' => !empty($data['formData']['paymentMethods']) ? $data['formData']['paymentMethods'][0] : null,
                'approval_status' => $data['formData']['approvalStatus'],
                'consultant_percentage' => $data['formData']['consultantCommission'],
                'designer_percentage' => $data['formData']['designerCommission'],
                'discount_percentage' => $data['formData']['discounts'],
                'taxes_percentage' => $data['formData']['taxes'],
                'technical_percentage' => $data['formData']['technicalCommission'],
                'third_party_percentage' => $data['formData']['thirdPartyCommission'],
            ]);

            // Processa os itens e serviços da lista
            if (isset($data['items']) && count($data['items']) > 0) {
                try {
                    // Tenta armazenar os itens
                    $this->proposalItemService->storeProposalItems($proposal->id, $data['items']);
                } catch (\Exception $e) {
                    // Se houver erro ao inserir os itens, revertendo a proposta
                    DB::rollBack(); // Reverte a transação
                    return [
                        'success' => false,
                        'message' => 'ERROR_SAVING_ITEMS',
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'PROPOSAL_CREATED_SUCCESSFULLY',
                'data' => $proposal
            ];

        } catch (\Exception $e) {
            // reverte toda a transacao
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'UNEXPECTED_ERROR',
            ];
        }
    }

    public function getProposal($id)
    {
        try {
            $proposal = Proposal::with(['vendor:id,name,email', 'client', 'items'])->findOrFail($id);
            return [
                'success' => true,
                'data' => $proposal
            ];
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'PROPOSAL_NOT_FOUND',
            ]);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'UNEXPECTED_ERROR',
            ];
        }
    }


    public function updateProposal($data, $id)
    {

        try {

            $proposal = Proposal::findOrFail($id);


        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'PROPOSAL_NOT_FOUND',
            ]);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'UNEXPECTED_ERROR',
            ];
        }
    }


    public function proposalExists($proposalId)
    {
        try {

            $proposal = Proposal::findOrFail($proposalId);

            return $proposal;

        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'PROPOSAL_NOT_FOUND',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'UNEXPECTED_ERROR',
            ];
        }
    }

    public function sendProposalSignatureLink($proposalID)
    {
        try {

            $proposal = Proposal::with('client')
                ->findOrFail($proposalID);

            $token = $this->generateToken($proposal);

            $proposal->signature_token = $token;
            $proposal->save();

            $signatureLink = env('APP_FRONTEND_URL') . '/proposals/signature/' . $token;

            //$proposal->client->email;
            $clientEmail = "felipe.barbosacl@gmail.com";

            EmailFactory::sendProposalSignatureEmail($clientEmail, $signatureLink);

            return [
                'success' => true,
                'message' => 'PROPOSAL_SENT_SUCCESSFULLY',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'ERROR_SENDING_EMAIL_PROPOSAL',
            ];
        }
    }

    private function hasValidToken($proposal)
    {
        if (!$proposal->signature_token) {
            return false;
        }
        try {

            $decoded = JWT::decode($proposal->signature_token, new Key(env('JWT_SECRET'), 'HS256')); // Algoritmo de hash


            $expiresAt = Carbon::createFromTimestamp($decoded->exp); // 'exp' ( chave de expiração no payload do JWT )

            return !$expiresAt->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function generateToken($proposal)
    {
        $payload = [
            'proposal_id' => $proposal->id,
            'exp' => Carbon::now()->addDays(5)->timestamp,
            'iat' => Carbon::now()->micro,
            'salt' => (string) Guid::uuid4(), // unicidade extra
        ];

        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }




    public function getSignaturePageData($token)
    {
        try {

            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));


            if (!isset($decoded->proposal_id) || !isset($decoded->exp)) {
                return [
                    'success' => false,
                    'message' => 'Token inválido.'
                ];
            }

            if ($decoded->exp < time()) {
                return [
                    'success' => false,
                    'message' => 'Link expirado.'
                ];
            }

            $proposal = Proposal::find($decoded->proposal_id);

            if (!$proposal) {
                return [
                    'success' => false,
                    'message' => 'Proposta não encontrada.'
                ];
            }

            return [
                'success' => true,
                'data' => $this->getProposal($proposal->id)['data']
            ];
        } catch (\Exception $e) {

            return [
                'success' => false,
                'message' => 'Erro ao processar o token'
            ];
        }
    }
}