<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Franquias;
use App\Services\ManagementAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    protected $managementAPI;

    public function __construct(ManagementAPI $managementAPI)
    {
        $this->managementAPI = $managementAPI;
    }
    public function checkout(Request $request){
        $request->validate([
            'gateway' => 'required|string', 
            'franquia_id' => 'required|integer',
            'amount' => 'required|numeric', 
            'payer_info' => 'required|array',
            'payer_info.cpf_cnpj' => 'required|string', 
            'payer_info.document_type' => 'required|string|in:FISICA,JURIDICA', 
            // 'payer_info.name' => 'required|string', 
            // 'payer_info.address' => 'required|string',
            // 'payer_info.city' => 'required|string', 
            // 'payer_info.state' => 'required|string|size:2', 
            // 'payer_info.zip_code' => 'required|string', 
        ]);
    
        $data = [
            'amount' => $request->input('amount'),
            'payer_info' => $request->input('payer_info'),
            'payment_type' => $request->input('payment_type'),
        ];
        
        $franchise = Franquias::find($request->input('franquia_id'));
        $config = [
            'chave_publica_inter' => $franchise->chave_publica_inter,
            'chave_secreta_inter' => $franchise->chave_secreta_inter,
            'chave_pix_inter' => $franchise->chave_pix_inter,
            'certificado_inter' => Storage::disk('private')->path($franchise->certificado_inter),
            'chave_inter'       => Storage::disk('private')->path($franchise->chave_inter),
        ];
    
        try {
             
             
             $response = $this->managementAPI->start('Payments', $request->input('gateway'), $data, $config);
             
             
             return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
