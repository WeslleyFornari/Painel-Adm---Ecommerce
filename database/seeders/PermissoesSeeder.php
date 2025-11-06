<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permissoes;
use App\Models\Paginas;

class PermissoesSeeder extends Seeder
{
    public function run()
    {
        $usuarios = User::all();

        foreach ($usuarios as $usuario) {
            $permissoes = Permissoes::where('id_user', $usuario->id)->get();

            if ($permissoes->count() == 0) {
                $paginas = Paginas::all();

                foreach ($paginas as $pagina) {
                    $permissao = new Permissoes();
                    $permissao->id_user = $usuario->id;
                    $permissao->id_pagina = $pagina->id;

                    if ($usuario->role == 'franqueado') {
                        if ($usuario->franquia->tipo_franqueado == 'toy') {
                            if (in_array($pagina->slug, ['produtos', 'dashboard', 'relatorios', 'calendarios', 'clientes', 'pedidos', 'estoque', 'regiao_atendida', 'cep_bloqueado', 'cupons', 'agenda', 'marcas'])) {
                                $permissao->visualizar = 'sim';
                                $permissao->criar = 'sim';
                                $permissao->editar = 'sim';
                                $permissao->deletar = 'sim';
                            } else {
                                $permissao->visualizar = 'nao';
                                $permissao->criar = 'nao';
                                $permissao->editar = 'nao';
                                $permissao->deletar = 'nao';
                            }
                        } else {
                            if (in_array($pagina->slug, ['dashboard', 'relatorios', 'calendarios', 'clientes', 'pedidos', 'estoque', 'regiao_atendida', 'cep_bloqueado'])) {
                                $permissao->visualizar = 'sim';
                                $permissao->criar = 'sim';
                                $permissao->editar = 'sim';
                                $permissao->deletar = 'sim';
                            } else {
                                $permissao->visualizar = 'nao';
                                $permissao->criar = 'nao';
                                $permissao->editar = 'nao';
                                $permissao->deletar = 'nao';
                            }
                        }
                    } elseif($usuario->role == 'user') {
                        $permissao->visualizar = 'nao';
                        $permissao->criar = 'nao';
                        $permissao->editar = 'nao';
                        $permissao->deletar = 'nao';
                    }
                    else{
                        $permissao->visualizar = 'sim';
                        $permissao->criar = 'sim';
                        $permissao->editar = 'sim';
                        $permissao->deletar = 'sim';
                    }

                    $permissao->save();
                }
            }
        }
    }
}
