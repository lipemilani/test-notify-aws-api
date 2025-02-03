<?php

namespace App\Services;

use Aws\Sns\SnsClient;
use Illuminate\Support\Facades\Log;

class AwsSnsService
{
    /**
     * @var SnsClient
     */
    protected $sns;

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Foundation\Application|mixed|null
     */
    protected $topicArn;

    public function __construct()
    {
        $this->sns = new SnsClient([
            'region'      => config('services.sns.region'),
            'version'     => 'latest',
            'credentials' => [
                'key'    => config('services.sns.key'),
                'secret' => config('services.sns.secret'),
            ],
        ]);

        $this->topicArn = config('services.sns.topic');
    }

    /**
     * @param string $message
     * @param array $data
     * @return bool
     */
    public function publishMessage(string $message, array $data = []): bool
    {
        try {
            $payload = [
                'Message'  => json_encode(['message' => $message, 'data' => $data]),
                'TopicArn' => $this->topicArn,
            ];

            $this->sns->publish($payload);

            Log::info('Mensagem enviada para SNS', $payload);

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao enviar mensagem SNS: ' . $e->getMessage());
            return false;
        }
    }
}
