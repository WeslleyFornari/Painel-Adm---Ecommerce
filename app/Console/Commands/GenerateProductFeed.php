<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Produtos;
use Illuminate\Support\Facades\Log;
use App\Services\FeedLogService;


class GenerateProductFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-product-feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML feed for Google Shopping';

    /**
     * Execute the console command.
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $error = null;
        $start = date("Y-m-d H:i:s");
        $merchant = Produtos::getMerchantProducts();

        $xml = new \SimpleXMLElement('<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0"/>');

        $channel = $xml->addChild('channel');
        $channel->addChild('title', 'Google Store');
        $channel->addChild('link', 'https://store.google.com');
        $channel->addChild('description', 'Este é um documento padrão RSS 2.0');

        foreach ($merchant as $product) {
            $item = $channel->addChild('item');
            
            $item->addChild('g:id', $product['id'], 'http://base.google.com/ns/1.0');
            $item->addChild('g:title', htmlspecialchars($product['title']), 'http://base.google.com/ns/1.0');
            $item->addChild('g:description', htmlspecialchars($product['description']), 'http://base.google.com/ns/1.0');
            $item->addChild('g:link', $product['link'], 'http://base.google.com/ns/1.0');
            $item->addChild('g:image_link', $product['image_link'], 'http://base.google.com/ns/1.0');
            $item->addChild('g:price', number_format($product['price'], 2, '.', '') . ' BRL', 'http://base.google.com/ns/1.0');
            $item->addChild('g:brand', $product['brand'], 'http://base.google.com/ns/1.0');
            $item->addChild('g:availability', $product['in_stock'] ? 'in_stock' : 'out_of_stock', 'http://base.google.com/ns/1.0');
            $item->addChild('g:mpn', $product['id'], 'http://base.google.com/ns/1.0');
        }

        try{
            $xml->asXML(storage_path('app/public/products.xml'));
        } catch (Excepition $e) {
            $error = $e;
        }

        $this->info('Feed generated successfully!');
        
        $fim = date("Y-m-d H:i:s");

        $sucesso = is_null($error) ? true : $error;

        $qtdProdutos = count($merchant);
        $horaInicio = $start;
        $horaFim = $fim;
        $sucesso = $sucesso;

        // insert log in file log laravel
        Log::info([
            'qtd_produtos' => $qtdProdutos,
            'hora_inicio' => $horaInicio,
            'hora_fim' => $horaFim,
            'Sucesso' => $sucesso
        ]);

        $feedLogService = new FeedLogService();
        
        $feedLogService->saveLog($qtdProdutos, $horaInicio, $horaFim, $sucesso);
    }
}