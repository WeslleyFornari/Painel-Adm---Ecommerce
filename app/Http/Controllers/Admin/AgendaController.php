<?php

namespace App\Http\Controllers\Admin;

use App\Models\PeriodosValores;
use App\Models\ProdutoCategoria;
use App\Models\ProdutosCaracteristicas;
use App\Models\ProdutosFotos;
use App\Models\ProdutosVideos;
use App\Models\Agenda;
use App\Models\Franquias;
use App\Models\Marca;
use Illuminate\Http\Request;
use App\Models\Produtos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgendaController extends Controller
{

    public function index(Request $request)
    {
        if(Auth::user()->role == 'franqueado'){
            $franquia = Franquias::find(Auth::user()->id_franquia);
            return redirect()->route('admin.agenda.bloqueios', ['id' => $franquia->id]); // Substitua 1 pelo ID de teste

        }
        else{
            $franquias = Franquias::where('status', 'ativo')->paginate(10);
            return view('admin.agenda.index', compact('franquias'));
        }
        
    }

    public function store(Request $request)
    {
        $data = $request->except('_token');

        $franquia = $data['id_franquia'];

        $datas = $request->input('datas');
        $datas_semana = $request->input('datas_semana');

        $data_total = '';

        if ($datas) {
            foreach ($datas['data_inicio'] as $index => $data_inicio) {
                $data = $data_inicio . ' ate ' . $datas['data_fim'][$index];
                $data_total .= ($data_total ? ', ' : '') . $data;
            }
        }

        $dia_semana_total = ''; 

        if ($datas_semana) {
            foreach ($datas_semana['dia_semana'] as $index => $data_semana) {
                if($index == '0'){
                    $dia_semana_total .= $data_semana;
                }
                else {
                    $dia_semana_total .= ', ' .$data_semana ;
                }
            }
        }

        $agenda = Agenda::where('id_franquia', $franquia)->first();

        if ($agenda){
            $agenda->update([
                'dias_bloqueados' => $data_total,
                'dias_semanas' => $dia_semana_total
            ]);
        }
        else{
            $agenda = Agenda::create([
                'id_franquia' => $franquia,
                'dias_bloqueados' => $data_total,
                'dias_semanas' => $dia_semana_total
            ]);
        }

        
        return response()->json(['status' => 'ok'], 200);
    }
    public function bloqueios($id)
    {
        $franquia = Franquias::find($id);

        $diasBloqueados = agenda_dias_bloqueados($franquia);
        return view('admin.agenda.bloqueios', compact('franquia', 'diasBloqueados'));
    }

}
