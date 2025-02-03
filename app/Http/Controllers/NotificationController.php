<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AwsSnsService;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * @var AwsSnsService
     */
    protected $snsService;

    /**
     * @param AwsSnsService $snsService
     */
    public function __construct(AwsSnsService $snsService)
    {
        $this->snsService = $snsService;
    }

    public function sendNotification(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
            'data'    => 'array|nullable',
        ]);

        $message = $request->input('message');
        $data    = $request->input('data', []);

        if ($this->snsService->publishMessage($message, $data)) {
            return response()->json(['message' => 'Notificação enviada com sucesso!'], 200);
        }

        return response()->json(['error' => 'Falha ao enviar notificação.'], 500);
    }
}
