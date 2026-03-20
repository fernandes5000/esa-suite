<?php

namespace App\Http\Controllers\Api\V1\Therapist;

use App\Http\Controllers\Controller;
use App\Http\Resources\EsaRequestResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\EsaRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RequestController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $requests = EsaRequest::where('status', 'pending')
            ->with(['user', 'pets'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->apiSuccess(EsaRequestResource::collection($requests));
    }

    public function approve($id)
    {
        $esaRequest = EsaRequest::find($id);

        if (!$esaRequest) {
            return $this->apiError('Request not found in Database with ID: ' . $id, 404);
        }

        $esaRequest->status = 'approved';
        $esaRequest->save();

        return $this->apiSuccess([
            'message' => 'Request approved successfully',
            'new_status' => $esaRequest->status,
            'id' => $esaRequest->id
        ]);
    }
    
    public function downloadPdf(EsaRequest $esaRequest)
    {
        if ($esaRequest->status !== 'approved') {
            abort(403, 'Document not approved yet.');
        }

        $data = [
            'user' => $esaRequest->user,
            'pets' => $esaRequest->pets,
            'request' => $esaRequest,
            'date' => now()->format('m/d/Y'),
        ];

        $pdf = Pdf::loadView('pdfs.certificate', $data);

        return $pdf->download('ESA-Certificate-' . $esaRequest->id . '.pdf');
    }
}