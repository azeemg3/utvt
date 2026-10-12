<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lms\SourceQuery;
use App\Repositories\Interfaces\LeadRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WebsiteLeadController extends Controller
{
    public function __construct(private LeadRepositoryInterface $leadInterface)
    {
    }

    /**
     * Create an open-channel CRM lead from The Royal Closet website contact form.
     */
    public function store(Request $request): JsonResponse
    {
        $expectedKey = (string) config('services.website_lead.key', '');
        if ($expectedKey !== '') {
            $provided = (string) $request->header('X-Website-Key', '');
            if (!hash_equals($expectedKey, $provided)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }

        // Honeypot — bots fill this; real users leave it empty
        if (filled($request->input('company'))) {
            return response()->json([
                'message' => 'Thank you! Our team will contact you shortly.',
                'ok' => true,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:80',
            'email' => 'required|email|max:160',
            'phone' => 'required|string|min:7|max:24',
            'projectType' => 'nullable|string|max:60',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $sourceId = (int) config('services.website_lead.source_id', 1);
        $source = SourceQuery::find($sourceId);
        if (!$source) {
            $source = SourceQuery::query()
                ->whereRaw('LOWER(name) LIKE ?', ['%website%'])
                ->orWhereRaw('LOWER(name) LIKE ?', ['%free quote%'])
                ->first();
            $sourceId = $source?->id ?? 1;
        }

        $projectType = trim((string) ($data['projectType'] ?? ''));
        $message = trim((string) ($data['message'] ?? ''));
        $details = trim(implode("\n", array_filter([
            $projectType !== '' ? "Project type: {$projectType}" : null,
            $message !== '' ? $message : null,
            'Submitted via The Royal Closet website contact form.',
        ])));

        $payload = [
            'type' => '2', // open channel (website) — no SPO takeover
            'contact_name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['phone'],
            'CID' => (int) config('services.website_lead.country_id', 233),
            'CTID' => 0,
            'source_id' => $sourceId,
            'other_details' => $details,
            'priority' => 2,
            'services' => [],
            'sectors' => $projectType !== '' ? [$projectType] : [],
            'class_travel' => 5,
        ];

        try {
            $lead = $this->leadInterface->store($payload);

            if (!$lead) {
                return response()->json([
                    'message' => 'Unable to create lead. Please try again or contact us by phone.',
                ], 500);
            }

            return response()->json([
                'ok' => true,
                'message' => 'Thank you! Our team will contact you within 24 hours.',
                'lead_id' => $lead->id,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Website lead create failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Unable to create lead. Please try again or contact us by phone.',
            ], 500);
        }
    }
}
