<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ManagementAPI
{

    public function start(string $baseNamespace, string $gateway, array $data, $config)
    {
        $className = "App\\Services\\$baseNamespace\\" . ucfirst($gateway) . "Service";

        if (!class_exists($className)) {
            throw new \Exception("Service class $className not found.");
        }

        $gatewayService = new $className($config);

        if (!method_exists($gatewayService, 'start')) {
            throw new \Exception("Method 'start' not defined in $className.");
        }

        Log::info("Starting process for $baseNamespace::$gateway", ['data' => $data]);

        $response = $gatewayService->start($data, $config);
        return $response;
    }

    public function handleWebhook(string $baseNamespace, string $gateway, string $event, array $data)
    {
        $className = "App\\Services\\$baseNamespace\\" . ucfirst($gateway) . "Service";

        if (!class_exists($className)) {
            throw new \Exception("Service class $className not found.");
        }

        $gatewayService = app($className);

        if (!method_exists($gatewayService, 'handleWebhook')) {
            throw new \Exception("Method 'handleWebhook' not defined in $className.");
        }

        Log::info("Handling webhook for $baseNamespace::$gateway", ['event' => $event, 'data' => $data]);

        return $gatewayService->handleWebhook($event, $data);
    }

}
