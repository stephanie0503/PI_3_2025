<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProposalService;
class ProposalController extends Controller
{

    public function __construct
    (
        protected ProposalService $proposalService
    ) {}

    public function index()
    {

        $data = $this->proposalService->getAllProposals();

        return response()->json($data);

    }

    public function store(Request $request)
    {
        $data = $request->all();
        $response = $this->proposalService->storeProposal($data);

        if (!$response['success']) {
            return response()->json(['message' => $response['message']], 404);
        }

        return response()->json(['message' => $response['message']], 201);
    }

    public function show($id)
    {
        $response = $this->proposalService->getProposal($id);

        if (!$response['success']) {
            return response()->json(['message' => $response['message']], 404);
        }

        return response()->json(['data' => $response['data']], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        // $response = $this->proposalService->updateProposal($data, $id);

        return response()->json(['id' => $id, 'dados' => $request->all()]);
    }

    public function sendSignatureLink($id)
    {

        // Delagar para o service

        $response = $this->proposalService->sendProposalSignatureLink($id);

        if (!$response['success']) {
            return response()->json(['message' => $response['message']], 404);
        }

        return response()->json(['message' => $response['message']], 201);
    }

    public function showSignaturePageData($token)
    {
        
        $response = $this->proposalService->getSignaturePageData($token);

        if (!$response['success']) {
            return response()->json(['message' => $response['message']], 404);
        }

        return response()->json($response['data'], 200);
    }



}
