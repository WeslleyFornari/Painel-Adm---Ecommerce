<?php

namespace App\Http\Controllers\Admin;

use App\Models\ItensPedidos;
use App\Models\Pedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\PDF as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    // public function gerar()
    // {

    //     $pdf = PDF::loadView('admin.pedidos.pdf.checklist_entrega');
    //     return $pdf->download('exemplo.pdf');
    // }
    public function pdfEntrega($id)
    {
        $pedido = Pedidos::find($id);

        $itens = ItensPedidos::where('id_pedido', $pedido->id)->get();

        $data_entrega = null;
        $data_devolucao = null;

        foreach ($itens as $item) {
            if ($item->entrega) {
                if ($data_entrega === null || $item->entrega->data_entrega < $data_entrega) {
                    $data_entrega = $item->entrega->data_entrega;
                }
                if ($data_devolucao === null || $item->entrega->data_devolucao > $data_devolucao) {
                    $data_devolucao = $item->entrega->data_devolucao;
                }
            }
        }

        $qrCode = QrCode::format('png')
                        ->size(300)
                        ->generate(route('admin.pedidos.preview', ['id' => $pedido->id]));
        $filePath = 'qrcodes/pedido_' . $pedido->id . '.png';
        file_put_contents(public_path($filePath), $qrCode);
        $fileUrl = 'https://admin.facilitrip.dvelopers.com.br/qrcodes/pedido_'.$pedido->id.'.png';
        $url = 'https://admin.facilitrip.dvelopers.com.br/uploads/';

        $pdf = \PDF::loadView('admin.pedidos.pdf.checklist_entrega', compact('pedido', 'data_entrega', 'data_devolucao', 'fileUrl', 'url'));
        return $pdf->download('checklist_entrega-' . $pedido->id . '.pdf');
    }
    
    public function pdfDevolucao($id)
    {
        $pedido = Pedidos::find($id);

        $itens = ItensPedidos::where('id_pedido', $pedido->id)->get();

        $data_entrega = null;
        $data_devolucao = null;

        foreach ($itens as $item) {
            if ($item->entrega) {
                if ($data_entrega === null || $item->entrega->data_entrega < $data_entrega) {
                    $data_entrega = $item->entrega->data_entrega;
                }
                if ($data_devolucao === null || $item->entrega->data_devolucao > $data_devolucao) {
                    $data_devolucao = $item->entrega->data_devolucao;
                }
            }
        }

        $qrCode = QrCode::format('png')
                        ->size(300)
                        ->generate(route('admin.pedidos.preview', ['id' => $pedido->id]));
        $filePath = 'qrcodes/pedido_' . $pedido->id . '.png';
        file_put_contents(public_path($filePath), $qrCode);
        $fileUrl = 'https://admin.facilitrip.dvelopers.com.br/qrcodes/pedido_'.$pedido->id.'.png';
        $url = 'https://admin.facilitrip.dvelopers.com.br/uploads/';

        $pdf = \PDF::loadView('admin.pedidos.pdf.checklist_devolucao', compact('pedido', 'data_entrega', 'data_devolucao', 'fileUrl', 'url'));
        return $pdf->download('checklist_devolucao-' . $pedido->id . '.pdf');
    }
    public function pdfEntregaDevolucao($id)
    {
        $pedido = Pedidos::find($id);

        $itens = ItensPedidos::where('id_pedido', $pedido->id)->get();

        $data_entrega = null;
        $data_devolucao = null;

        foreach ($itens as $item) {
            if ($item->entrega) {
                if ($data_entrega === null || $item->entrega->data_entrega < $data_entrega) {
                    $data_entrega = $item->entrega->data_entrega;
                }
                if ($data_devolucao === null || $item->entrega->data_devolucao > $data_devolucao) {
                    $data_devolucao = $item->entrega->data_devolucao;
                }
            }
        }

        // $qrCode = QrCode::size(50)->generate(route('admin.pedidos.preview', ['id' => $pedido->id]));

        $qrCode = QrCode::format('png')
                        ->size(300)
                        ->generate(route('admin.pedidos.preview', ['id' => $pedido->id]));
        $filePath = 'qrcodes/pedido_' . $pedido->id . '.png';
        file_put_contents(public_path($filePath), $qrCode);
        $fileUrl = 'https://admin.facilitrip.dvelopers.com.br/qrcodes/pedido_'.$pedido->id.'.png';

        $url = 'https://admin.facilitrip.dvelopers.com.br/uploads/';

        $pdf = \PDF::loadView('admin.pedidos.pdf.checklist_entrega_devolucao', compact('pedido', 'data_entrega', 'data_devolucao', 'fileUrl', 'url'));
        return $pdf->download('checklist_entrega_devolucao-' . $pedido->id . '.pdf');

        // return view('admin.pedidos.pdf.checklist_entrega_devolucao', compact('pedido', 'data_entrega', 'data_devolucao', 'fileUrl'));
    }
}