<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarcaSeeder extends Seeder
{
    public function run()
    {
        // Lista de marcas e suas informações associadas
        $marcas = [
            ['file' => '1681-maxi-cosi_logo.webp', 'type' => 'webp' , 'nome' => 'Maxi-Cosi'],
            ['file' => '1733239751-4moms.webp','type' => 'webp' , 'nome' => '4 Moms'],
            ['file' => '1733239751-chicco.webp','type' => 'webp' , 'nome' => 'Chicco'],
            ['file' => '1733239751-cosco.webp','type' => 'webp' , 'nome' => 'Cosco'],
            ['file' => '1733239751-fisher.webp','type' => 'webp' , 'nome' => 'Fisher-Price'],
            ['file' => '1733239751-girotondo.webp','type' => 'webp' , 'nome' => 'Girotondo'],
            ['file' => '1733239751-infantino.webp','type' => 'webp' , 'nome' => 'Infantino'],
            ['file' => '1733239751-ingenuity.webp','type' => 'webp' , 'nome' => 'Ingenuity'],
            ['file' => '1733239751-safety.webp','type' => 'webp' , 'nome' => 'Safety 1st'],
            ['file' => 'B8_logoslider_Nuna.webp','type' => 'webp' , 'nome' => 'Nuna'],
            ['file' => 'baby-pil.png','type' => 'png' , 'nome' => 'Baby Pil'],
            ['file' => 'bumbo-63eea447a017a.webp','type' => 'webp' , 'nome' => 'Bumbo'],
            ['file' => 'ecotoys-logo-1200x630.png','type' => 'png' , 'nome' => 'EcoToys'],
            ['file' => 'infanti-logo-22BA58F876-seeklogo.com.png','type' => 'png' , 'nome' => 'Infanti'],
            ['file' => 'logo-kiddo.webp','type' => 'webp' , 'nome' => 'Kiddo'],
            ['file' => '169870-auto-262.png','type' => 'png' , 'nome' => 'Galzerano'],
            ['file' => 'logo-voyage_24a9edba-261f-49f7-9d99-e83909025bcc_200x.webp','type' => 'webp' , 'nome' => 'Voyage'],
            ['file' => '692fd7f522f389d02464c1ca91a8cfdc.w1500.h1500.png','type' => 'png' , 'nome' => 'Mastela'],
            ['file' => 'd5dx5jztyxsqt2snsfil.webp','type' => 'webp' , 'nome' => 'Burigotto'],
            ['file' => 'transferir.png','type' => 'png' , 'nome' => 'Luckspuma'],
        ];

        foreach ($marcas as $marca) {
            $mediaId = DB::table('media')->insertGetId([
                'file' => $marca['file'],
                'alt' => '',
                'type' => $marca['type'],
                'folder_parent' => null,
                'folder' => 'uploads/marcas/',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('marcas')->insert([
                'media_id' => $mediaId,
                'nome' => $marca['nome'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $produtos = DB::table('produtos')->get();
        $imagemPadrao = 'logo-padrao.png';

        $mediaId = DB::table('media')->insertGetId([
            'file' => $imagemPadrao,
            'alt' => '',
            'type' => 'png',
            'folder_parent' => null,
            'folder' => 'uploads/marcas/',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($produtos as $produto) {
            $token = Str::uuid()->toString();

            $marcaExistente = DB::table('marcas')->where('nome', 'like', ['%' . $produto->marca . '%'])->first();
            if ($marcaExistente) {
                DB::table('produtos')->where('id', $produto->id)->update(['marca' => $marcaExistente->id]);
            } else {
                $novaMarcaId = DB::table('marcas')->insertGetId([
                    'media_id' => $mediaId,
                    'nome' => $produto->marca,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('produtos')->where('id', $produto->id)->update(['marca' => $novaMarcaId]);
            }
        }
    }
}
