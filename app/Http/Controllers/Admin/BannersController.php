<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannersController extends Controller
{
    

// LISTA
    public function index(Request $request){
        
        $id_banner = Auth::user()->id_banner;
        $banners = Banners::paginate(4);

        return view('admin.banners.index', compact('banners'));
    }

// NEW
    public function new(){
   
        return view('admin.banners.new');
    }

// STORE
    public function store(Request $request)
    {
        $data = $request->except('_token');

        $errors = [];

        if (!$request->input('id_media_desktop')) {
            $errors['id_media_desktop'] = ['A imagem de desktop é obrigatória.'];
        }

        if (!$request->input('id_media_mobile')) {
            $errors['id_media_mobile'] = ['A imagem de mobile é obrigatória.'];
        }

        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        $existingBanner = Banners::where('ordem', $request->input('ordem'))->first();
        
        if ($existingBanner) {
            return response()->json([
                'errors' => [
                    'ordem' => ['Esta ordem já está em uso.']
                ]
            ], 422); 
        }

        $countBanner = 0;
        if($request->input('tipo') == 'pop-up'){
            $countBanner = Banners::where('tipo', 'pop-up')->update([
                'status' => 'inativo'
            ]);
        }

        $status = $request->input('status') ?? 'inativo';

        $banner = new Banners();
        $banner->titulo = $request->input('titulo');
        $banner->ordem = $request->input('ordem');
        $banner->url = $request->input('url');
        $banner->tipo_franqueado = $request->input('tipo_franqueado');
        $banner->new_window = $request->input('new_window');
        $banner->id_media_desktop = $request->input('id_media_desktop');
        $banner->id_media_mobile = $request->input('id_media_mobile');
        $banner->tipo = $request->input('tipo');
        $banner->status = $status;

        $banner->save();
        
        return response()->json(['status' => 'ok', 'countBanner' => $countBanner]);
    }
// EDIT
    public function edit(Request $require,$id){

        $banner = Banners::find($id);
        
        return view('admin.banners.edit',compact('banner'));
    }

// UPDATE
public function update(Request $request, $id)
{
    $data = $request->except('_token');
    $banner = Banners::find($id);
    $errors = [];

    if (!$request->input('id_media_desktop')) {
        $errors['id_media_desktop'] = ['A imagem de desktop é obrigatória.'];
    }

    if (!$request->input('id_media_mobile')) {
        $errors['id_media_mobile'] = ['A imagem de mobile é obrigatória.'];
    }

    if (!empty($errors)) {
        return response()->json(['errors' => $errors], 422);
    }

    $existingBanner = Banners::where('ordem', $request->input('ordem'))
                             ->where('id', '!=', $id)
                             ->first();

    if ($existingBanner) {
        return response()->json([
            'errors' => [
                'ordem' => ['Esta ordem já está em uso.']
            ]
        ], 422);
    }

    $countBanner = 0;
    if($request->input('tipo') == 'pop-up'){
        $countBanner = Banners::where('tipo', 'pop-up')->update([
            'status' => 'inativo'
        ]);
    }

    $status = isset($data['status']) ? $data['status'] : 'inativo';

    $banner->titulo = $request->input('titulo');
    $banner->ordem = $request->input('ordem');
    $banner->url = $request->input('url');
    $banner->new_window = $request->input('new_window');
    $banner->tipo_franqueado = $request->input('tipo_franqueado');
    $banner->id_media_desktop = $request->input('id_media_desktop');
    $banner->id_media_mobile = $request->input('id_media_mobile');
    $banner->tipo = $request->input('tipo');
    $banner->status = $status;

    $banner->save();

    return response()->json([
        'status' => 'ok',
        'countBanner' => $countBanner
    ], 200);
}
// DELETE
    public function delete(Request $request,$id)   {

        $banner = Banners::find($id);
    
        $banner->delete();

        return response()->json(['Status' => 'Deletado com sucesso', 200]);
    }

// // TOGGLE SWITCH
//     public function status(Request $request){

//         $data = $request->all();
    
//         Banners::where('id',$data['id'])->update(['status'=> $data['status']]);

//         return response()->json([

//             'status' => 'Alterado com sucesso' 
//         ]);

//     }
public function mudarStatus($id = null){
    $banner = Banners::find($id);

    if ($banner->status == 'ativo'){
        $banner->status = 'inativo';
    }
    else{
        $banner->status = 'ativo';
    }

    $banner->save();

    return response()->json(['status'=>'ok']);
}
}
