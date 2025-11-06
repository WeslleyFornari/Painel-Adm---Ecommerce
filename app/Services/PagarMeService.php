<?php

namespace App\Services;

use App\Models\DadosClientes;
use App\Models\ExtratoSplit;
use App\Models\Pedidos;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PagarMeService
{
    protected $apiKey;
    protected $client;

    public function __construct()
    {
        $this->apiKey = env('PAGARME_API_KEY');
        $this->client = new Client([
            'base_uri' => 'https://api.pagar.me/core/v5/',
        ]);
    }

    public function postCustomer(array $data)
    {
        $user = User::where('email', $data['email'])->first();
        if ($user->dados->id_gateway) {
            return $user->dados->id_gateway;
        }
        try {
            $response = $this->client->post('customers', [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $errorMessage = $e->getResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage();
            $errorJson = json_decode($errorMessage);
            Log::error('Erro ao criar cliente na Pagar.me: ' . $errorMessage);
            return [
                'error' => true,
                'message' => $errorJson,
            ];
        }
    }

    public function putCustomer(array $data, $id)
    {
        try {
            $response = $this->client->put("customers/{$id}", [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar usuário na Pagar.me: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteCustomer($id)
    {
        try {
            $response = $this->client->delete("customers/{$id}", [
                'headers' => $this->getHeaders(),
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir usuário na Pagar.me: ' . $e->getMessage());
            return null;
        }
    }

    public function getCustomer($id)
    {
        try {
            $response = $this->client->get("customers/{$id}", [
                'headers' => $this->getHeaders(),
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Erro ao obter usuário na Pagar.me: ' . $e->getMessage());
            return null;
        }
    }

    public function postCartao(array $paymentData)
    {
        try {
            $order = $this->createOrder($paymentData, 'cartao');
            return $order;
        } catch (\Exception $e) {
            $errorMessage = $e->getResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage();
            $errorJson = json_decode($errorMessage);
            Log::error('Erro ao criar pedido Cartão na Pagar.me: ' . $errorMessage);
            return [
                'error' => true,
                'message' => $errorJson,
            ];
        }
    }

    public function postPix($paymentData)
    {
        try {
            $order = $this->createOrder($paymentData, 'pix');
            return $order;
        } catch (\Exception $e) {
            $errorMessage = $e->getResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage();
            $errorJson = json_decode($errorMessage);
            Log::error('Erro ao criar pedido PIX na Pagar.me: ' . $errorMessage);
            return [
                'error' => true,
                'message' => $errorJson,
            ];
        }
    }

    // Cria um pedido na Pagar.me
    protected function createOrder(array $orderData, string $type)
    {
        if (!$orderData['customer_id']) {
            $pedido = Pedidos::find($orderData['idPedido']);
            $cliente = $pedido->cliente;
            $dadosCliente = $cliente->dados;

            if ($dadosCliente && $dadosCliente->id_gateway) {
                $orderData['customer_id'] = $dadosCliente->id_gateway;
            } else {
                $documentType = isset($dadosCliente->cpf) ? 'cpf' : 'cnpj';
                $document = isset($dadosCliente->cpf) ? $dadosCliente->cpf : $dadosCliente->cnpj;
                $document = preg_replace('/[\.\-]/', '', $document);

                $type = isset($dadosCliente->cpf) ? 'individual' : 'corporation';

                $cleanPhoneNumber = preg_replace('/[^\d]/', '', $dadosCliente->celular);

                $arrayCliente = [
                    "name" => $cliente->name,
                    "email" => $cliente->email,
                    "document" => $document,
                    "document_type" => $documentType,
                    "type" => $type,
                    "phones" => [
                        "home_phone" => [
                            "country_code" => "55",
                            "area_code" => "12",
                            "number" => substr($cleanPhoneNumber, 3),
                        ],
                        "mobile_phone" => [
                            "country_code" => "55",
                            "area_code" => "12",
                            "number" => substr($cleanPhoneNumber, 3),
                        ],
                    ],
                ];

                $clienteGateway = $this->postCustomer($arrayCliente);

                if (isset($clienteGateway['id']) && !empty($clienteGateway['id'])) {
                    DadosClientes::where('id_user', $cliente->id)->update(['id_gateway' => $clienteGateway['id']]);
                    $orderData['customer_id'] = $clienteGateway['id'];
                }
            }
        }
        $data = $this->formatOrdemItens($orderData, $type);
        try {
            $response = $this->client->post('orders', [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);
            return json_decode($response->getBody(), true);

        } catch (\Exception $e) {
            $errorMessage = $e->getResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage();
            $errorJson = json_decode($errorMessage);
            Log::error('Erro ao criar pedido na Pagar.me: ' . $errorMessage);
            return [
                'error' => true,
                'message' => $errorJson,
            ];
        }
    }

    protected function formatOrdemItens(array $orderData, string $type)
    {
        $formated = [];
        // $getSplitValues = $this->getSplitValues($orderData['idPedido'], intval(str_replace([',', '.'], '', $orderData['items'][0]['amount'])));
        $getSplitValues = $this->getSplitValues($orderData['idPedido'], $orderData['items'][0]['amount']);
        if ($type == 'cartao') {
            $formated = [
                "customer_id" => $orderData['customer_id'],
                "items" => [
                    [
                        "amount" => intval(str_replace([',', '.'], '', $orderData['items'][0]['amount'])),
                        "description" => $orderData['items'][0]['description'],
                        "quantity" => intval($orderData['items'][0]['quantity']),
                        "code" => $orderData['items'][0]['code'],
                    ],
                ],
                "payments" => [
                    [
                        "credit_card" => [
                            "card" => [
                                "billing_address" => [
                                    "line_1" => $orderData['payments'][0]['credit_card']['card']['billing_address']['line_1'],
                                    "zip_code" => $orderData['payments'][0]['credit_card']['card']['billing_address']['zip_code'],
                                    "city" => $orderData['payments'][0]['credit_card']['card']['billing_address']['city'],
                                    "state" => $orderData['payments'][0]['credit_card']['card']['billing_address']['state'],
                                    "country" => $orderData['payments'][0]['credit_card']['card']['billing_address']['country'],
                                ],
                                "number" => $orderData['payments'][0]['credit_card']['card']['number'],
                                "holder_name" => $orderData['payments'][0]['credit_card']['card']['holder_name'],
                                "exp_month" => intval($orderData['payments'][0]['credit_card']['card']['exp_month']),
                                "exp_year" => intval($orderData['payments'][0]['credit_card']['card']['exp_year']),
                                "cvv" => $orderData['payments'][0]['credit_card']['card']['cvv'],
                            ],
                            "operation_type" => $orderData['payments'][0]['credit_card']['operation_type'],
                            "installments" => intval($orderData['payments'][0]['credit_card']['installments']) ?? 1,
                            "statement_descriptor" => $orderData['payments'][0]['credit_card']['statement_descriptor'],
                        ],
                        "split" => $getSplitValues,
                        "payment_method" => $orderData['payments'][0]['payment_method'],
                    ],
                ],
            ];
        } else {
            $formated = [
                "code" => $orderData['customer_id'],
                "customer_id" => $orderData['customer_id'],
                "items" => [
                    [
                        'amount' => intval(str_replace([',', '.'], '', $orderData['items'][0]['amount'])),
                        'description' => 'Compra Facilitrip',
                        'quantity' => intval($orderData['items'][0]['quantity']),
                        'code' => '001',
                    ],
                ],
                "payments" => [
                    [
                        "Pix" => [
                            "expires_in" => 3600,
                        ],
                        "split" => $getSplitValues,
                        "payment_method" => "pix",
                    ],
                ],
            ];
        }
        return json_decode(json_encode($formated));
    }

    protected function getSplitValues(int $idPedido, string $totalValue)
    {
        $orderInfo = Pedidos::with('franquia')->findOrFail($idPedido);

        $pedido = Pedidos::find($idPedido);

        $franqueadoInfo = $orderInfo->franquia;

        if ($pedido->tipo == 'manual') {
            $percentual = $franqueadoInfo->percentual_manual_franqueado;
        } else if ($pedido->tipo = 'automatico') {
            $percentual = $franqueadoInfo->percentual_automatico_franqueado;
        }
        if (!$franqueadoInfo || !isset($percentual, $franqueadoInfo->cod_franqueado)) {
            Log::error('Informações de franqueado estão incompletas para o pedido: ' . $idPedido);
            throw new \Exception('Informações de franqueado estão incompletas.');
        }

        $totalValue = floatval(str_replace([',', '.'], ['', '.'], $totalValue));
        $totalValueCentavos = intval(round($totalValue * 100));

        $percentualFranqueado = intval(str_replace([',', '.'], ['', '.'], $percentual));
        $valorFranqueado = intval(round(($percentualFranqueado / 100) * $totalValueCentavos));

        $percentualRestante = 100 - $percentualFranqueado;

        Log::info('Valor franqueado: ' . $percentualFranqueado);
        Log::info('Valor restante:' . $percentualRestante);

        if ($percentualRestante < 0) {
            Log::error('Percentuais de split somam mais de 100% para o pedido: ' . $idPedido);
            throw new \Exception('Percentuais de split estão incorretos.');
        }

        $splitValues = [
            [
                'amount' => $percentualFranqueado,
                'recipient_id' => $franqueadoInfo->cod_franqueado,
                'type' => 'percentage',
                "options" => [
                    "charge_processing_fee" => true,
                    "charge_remainder_fee" => true,
                    "liable" => true,
                ],
            ],
        ];

        if ($percentualRestante > 0) {
            $splitValues[] = [
                'amount' => $percentualRestante,
                'recipient_id' => env('PAGARME_DEFAULT_RECIPIENT'),
                'type' => 'percentage',
                "options" => [
                    "charge_processing_fee" => false,
                    "charge_remainder_fee" => false,
                    "liable" => false,
                ],
            ];
        }

        ExtratoSplit::create([
            'id_ordem' => $idPedido,
            'id_franquia' => $orderInfo->id_franquia,
            'valor_split' => $valorFranqueado / 100,
            'percentual_split' => $percentualFranqueado,
        ]);

        return $splitValues;
    }

    // Obtém os cabeçalhos para requisições à API da Pagar.me
    protected function getHeaders()
    {
        return [
            'authorization' => 'Basic ' . base64_encode($this->apiKey . ':'),
            'content-type' => 'application/json',
        ];
    }

    public function hookReturn(string $eventType, array $dadosHook)
    {
        Log::info("Recebendo dados de pagamento da Pagar.me", ['data' => $dadosHook]);

        try {
            $method = 'handle' . ucfirst($eventType) . 'Event';
            if (method_exists($this, $method)) {
                return $this->$method($dadosHook);
            }

            return response()->json(['message' => 'Invalid webhook type'], 400);
        } catch (\Exception $e) {
            Log::error('Erro ao processar o webhook da Pagar.me: ' . $e->getMessage(), ['data' => $dadosHook]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    protected function handleOrderEvent(array $data)
    {
        $gatewayOrderId = $data['data']['id'] ?? null;
        $status = $data['data']['status'] ?? null;
        $transactionType = $data['data']['charges'][0]['last_transaction']['transaction_type'] ?? null;

        if (!$gatewayOrderId || !$status || !$transactionType) {
            Log::error('Dados incompletos recebidos no webhook da Pagar.me.', ['data' => $data]);
            return response()->json(['message' => 'Incomplete data received'], 400);
        }

        $order = Pedidos::where('id_transacao', $gatewayOrderId)->first();

        if (!$order) {
            Log::error('Pedido não encontrado para o ID de transação: ' . $gatewayOrderId, ['data' => $data]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($status === "paid") {
            if ($transactionType === 'credit_card') {
                $order->id_status = 4;
            } elseif ($transactionType === 'pix') {
                $order->id_status = 4;
            }
            $order->update();
            Log::info('Pedido processado com sucesso: ' . $gatewayOrderId);
            return response()->json(['message' => 'Order status updated successfully'], 200);
        } elseif ($status === "failed") {
            $order->id_status = 5;
            $order->update();
            Log::info('Pagamento falhou para o pedido: ' . $gatewayOrderId);
            return response()->json(['message' => 'Order payment failed'], 200);
        }
    }
    public function translate($mensagemErro)
    {
        if (isset($mensagemErro->errors->{"order.payments[0].credit_card.card"})) {
            $erros = $mensagemErro->errors->{"order.payments[0].credit_card.card"};
            $errorTranslations = [
                'The number field is not a valid card number' => 'O número do cartão não é válido.',
                'The card brand is not supported' => 'A bandeira do cartão não é suportada.',
                'The expiration month is invalid' => 'O mês de expiração é inválido.',
                'The expiration year is invalid' => 'O ano de expiração é inválido.',
                'The cardholder name is required' => 'O nome do titular do cartão é obrigatório.',
                'The card security code is invalid' => 'O código de segurança do cartão é inválido.',
                'Card expired.' => 'O cartão está expirado.',
            ];

            return $this->translateErrors($erros, $errorTranslations);
        }

        if (isset($mensagemErro->errors->{"order.payments[0].pix"})) {
            $erros = $mensagemErro->errors->{"order.payments[0].pix"};
            $errorTranslations = [
                'The pix key is required' => 'A chave Pix é obrigatória.',
                'The pix key is invalid' => 'A chave Pix é inválida.',
                'The expiration date is required' => 'A data de expiração é obrigatória.',
                'The expiration date is invalid' => 'A data de expiração é inválida.',
                'The payment method is not supported' => 'O método de pagamento não é suportado.',
                'The amount must be greater than zero' => 'O valor deve ser maior que zero.',
            ];

            return $this->translateErrors($erros, $errorTranslations);
        }

        if (isset($mensagemErro->errors->{"order.user"})) {
            $erros = $mensagemErro->errors->{"order.user"};
            $errorTranslations = [
                'cpf_invalid' => 'O CPF fornecido é inválido. Por favor, verifique e tente novamente.',
                'name_invalid' => 'O nome deve conter pelo menos dois nomes. Por favor, verifique e tente novamente.',
                'birthdate_invalid' => 'A data de nascimento fornecida é inválida. Por favor, verifique e tente novamente.',
                'email_invalid' => 'O e-mail fornecido é inválido. Por favor, verifique e tente novamente.',
                'phone_invalid' => 'O número de telefone fornecido é inválido. Por favor, verifique e tente novamente.',
                'address_invalid' => 'O endereço fornecido está incompleto. Por favor, preencha todos os campos e tente novamente.',
                'document_invalid' => 'O documento de identidade fornecido é inválido. Por favor, verifique e tente novamente.',
            ];

            return $this->translateErrors($erros, $errorTranslations);
        }

        return ['Ocorreu um erro desconhecido.'];
    }

    private function translateErrors($erros, $errorTranslations)
    {
        $mensagensTraduzidas = [];

        foreach ($erros as $erro) {
            if (isset($errorTranslations[$erro])) {
                $mensagensTraduzidas[] = $errorTranslations[$erro];
            } else {
                $mensagensTraduzidas[] = $erro;
            }
        }

        return $mensagensTraduzidas;
    }
}
