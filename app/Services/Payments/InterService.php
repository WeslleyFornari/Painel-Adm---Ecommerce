<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;


class InterService
{
    protected $apiUrl;
    protected $clientId;
    protected $clientSecret;
    protected $chavePix;
    protected string $certPath;
    protected string $keyPath;

    public function __construct(array $config)
    {
        $this->apiUrl = env('INTER_API_URL');
        $this->clientId = $config['chave_publica_inter'];
        $this->clientSecret = $config['chave_secreta_inter'];
        $this->chavePix = $config['chave_pix_inter'];
        $this->certPath = $config['certificado_inter'];
        $this->keyPath = $config['chave_inter'];

        Log::info('InterService initialized', [
            'apiUrl' => $this->apiUrl,
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'chavePix' => $this->chavePix,
            'certPath' => $this->certPath,
            'keyPath' => $this->keyPath,
        ]);
    }

    public function start($data, $apiKey)
    {
        // Gera a cobrança PIX
        $pixCharge = $this->generatePixCharge($data);
        
        // Gera o QR Code em base64 a partir do pixCopiaECola
        $qrCodeBase64 = $this->generateQrCodeBase64($pixCharge['pixCopiaECola']);
    
        // Gera a cobrança de Boleto
        // $boletoCharge = $this->generateBoletoCharge($data);
        // $boletoInfo = $this->getBoletoInfo($boletoCharge['codigoSolicitacao']);
        // $boletoPdf = $this->getBoletoPdf($boletoCharge['codigoSolicitacao']);
    
        // Retorna os dados de PIX e Boleto
        return [
            'pix' => [
                'chargeNumber' => $pixCharge['txid'],
                'qrCodeInfo' => [
                    'url' => $pixCharge['loc']['location'],
                    'payload' => $pixCharge['pixCopiaECola'],
                    'qrCodeBase64' => $qrCodeBase64 // Adiciona o QR Code em base64
                ]
            ],
            // 'boleto' => [
            //     'chargeNumber' => $boletoCharge['codigoSolicitacao'],
            //     'boletoInfo' => $boletoInfo['boleto'],
            //     'pdf' => $boletoPdf['pdf']
            // ]
        ];
    }

    protected function generateQrCodeBase64(string $pixPayload): string
    {
        // Usando a biblioteca Simple QrCode para gerar o QR Code
        $qrcode = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($pixPayload);
        
        // Converte a imagem para base64
        return base64_encode($qrcode);
    }

    protected function generatePixCharge($data)
    {
        $pixData = [
            'calendario' => [
                'expiracao' => 3600 // Tempo de expiração em segundos
            ],
            'devedor' => [
                'cpf' => $data['payer_info']['cpf_cnpj'],
                'nome' => $data['payer_info']['name']
            ],
            'valor' => [
                'original' => number_format($data['amount'], 2, '.', '')
            ],
            'chave' => $this->chavePix, // Sua chave PIX cadastrada no Banco Inter
            'solicitacaoPagador' => $data['description'] ?? 'Pagamento via PIX'
        ];

        $endpoint = $this->apiUrl . '/pix/v2/cob';

        Log::info('Gerando cobrança PIX', ['endpoint' => $endpoint, 'data' => $pixData]);

        $response = $this->makeCurlRequest($endpoint, 'POST', $pixData);

        return $response;
    }

    protected function generateBoletoCharge($data)
    {
        $boletoData = [
            'seuNumero' => Str::random(10),
            'valorNominal' => number_format($data['amount'], 2, '.', ''),
            'dataVencimento' => date('Y-m-d'),
            'pagador' => [
                'cpfCnpj' => $data['payer_info']['cpf_cnpj'],
                'tipoPessoa' => $data['payer_info']['document_type'], // [FISICA, JURIDICA]
                'nome' => $data['payer_info']['name'],
                'endereco' => $data['payer_info']['address'],
                'cidade' => $data['payer_info']['city'],
                'uf' => $data['payer_info']['state'], // Siglas dos estados brasileiros
                'cep' => $data['payer_info']['zip_code'],
            ],
        ];

        $endpoint = $this->apiUrl . '/cobranca/v3/cobrancas';

        Log::info('Gerando cobrança de Boleto', ['endpoint' => $endpoint, 'data' => $boletoData]);

        $response = $this->makeCurlRequest($endpoint, 'POST', $boletoData);

        return $response;
    }

    protected function getBoletoInfo($charge)
    {
        $endpoint = $this->apiUrl . '/cobranca/v3/cobrancas/' . $charge;

        Log::info('Consultando informações do Boleto', ['endpoint' => $endpoint]);

        $response = $this->makeCurlRequest($endpoint, 'GET', null);

        return $response;
    }

    protected function getBoletoPdf($charge)
    {
        $endpoint = $this->apiUrl . '/cobranca/v3/cobrancas/' . $charge . '/pdf';

        Log::info('Obtendo PDF do Boleto', ['endpoint' => $endpoint]);

        $response = $this->makeCurlRequest($endpoint, 'GET', null);

        return $response;
    }

    protected function makeCurlRequest($url, $method, $data, $useAuth = true)
    {
        // dd(public_path('certificates/interMatheus/Sandbox_InterAPI_Certificado.crt'));
        $headers = [
            'Content-Type: application/json',
        ];

        if ($useAuth) {
            $accessToken = $this->getAccessToken();
            $headers[] = "Authorization: Bearer $accessToken";
        }

        Log::info('Fazendo requisição cURL', ['url' => $url, 'method' => $method, 'data' => $data, 'accessToken' => $accessToken]);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSLCERT, $this->certPath);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->keyPath);
        // curl_setopt($ch, CURLOPT_SSLCERT, public_path('certificates/InterMatheus/Sandbox_InterAPI_Certificado.crt'));
        // curl_setopt($ch, CURLOPT_SSLKEY, public_path('certificates/InterMatheus/Sandbox_InterAPI_Chave.key'));

        // curl_setopt($ch, CURLOPT_SSLCERT, storage_path('private/certificates/interMatheus/Sandbox_InterAPI_Certificado.crt'));
        // curl_setopt($ch, CURLOPT_SSLKEY, storage_path('private/certificates/interMatheus/Sandbox_InterAPI_Chave.key'));

        // curl_setopt($ch, CURLOPT_SSLCERT, Storage::disk('local')->path('private/interMatheus/Sandbox_InterAPI_Certificado.crt'));
        // curl_setopt($ch, CURLOPT_SSLKEY, Storage::disk('local')->path('private/interMatheus/Sandbox_InterAPI_Chave.key'));

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 segundos
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10 segundos

        $response = curl_exec($ch);
        Log::info('Resposta da requisição CURL', ['response' => $response]);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("Erro na requisição cURL: $error");
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    protected function getAccessToken()
    {
        $authUrl = env('INTER_LOGIN_URL');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $authUrl);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_SSLCERT, $this->certPath);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->keyPath);

        // curl_setopt($ch, CURLOPT_SSLCERT, public_path('certificates/InterMatheus/Sandbox_InterAPI_Certificado.crt'));
        // curl_setopt($ch, CURLOPT_SSLKEY, public_path('certificates/InterMatheus/Sandbox_InterAPI_Chave.key'));

        // curl_setopt($ch, CURLOPT_SSLCERT, storage_path('private/certificates/interMatheus/Sandbox_InterAPI_Certificado.crt'));
        // curl_setopt($ch, CURLOPT_SSLKEY, storage_path('private/certificates/interMatheus/Sandbox_InterAPI_Chave.key'));

        // curl_setopt($ch, CURLOPT_SSLCERT, Storage::disk('local')->path('private/interMatheus/Sandbox_InterAPI_Certificado.crt'));
        // curl_setopt($ch, CURLOPT_SSLKEY, Storage::disk('local')->path('private/interMatheus/Sandbox_InterAPI_Chave.key'));

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'pix.read pix.write cob.read cob.write boleto.read boleto.write boleto-cobranca.write boleto-cobranca.read cobv.write cobv.read',
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 segundos
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 10 segundos
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        Log::info('Fazendo requisição token:', ['url' => $authUrl, 'client_id' => $this->clientId, 'client_secret' => $this->clientSecret]);


        if ($response === false) {
            throw new \Exception('Erro ao obter token: ' . curl_error($ch));
        }

        curl_close($ch);

        Log::info('Resposta da API de autenticação', ['response' => $response]);

        $responseData = json_decode($response, true);

        if (isset($responseData['access_token'])) {
            Log::info('Token de acesso', ['response' => $responseData['access_token']]);
            return $responseData['access_token'];
        }

        throw new \Exception('Erro ao obter access_token. Resposta inválida: ' . $response);
    }
}