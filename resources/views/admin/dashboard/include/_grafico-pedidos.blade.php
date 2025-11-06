<div class="row">
    @include('admin.dashboard.include._begin-pedidos')
</div>

@if (Auth::user()->role != "franqueado")
<div class="form-group col-sm-4 d-none">
    <span class="titulo"> Franquias: </span>
    <select class="form-select" name="franqueado" id="franqueado">
        <option value="todos" selected>Todos</option>
        @foreach($franquias as $franquia)
        <option value="{{$franquia->id}}">{{ $franquia->nome_franquia}}</option>
        @endforeach
    </select>
</div>
@else
<input type="hidden" name="franqueado" id="franqueado" value="{{Auth::user()->id_franquia}}">
@endif

<div class="select">

</div>

<div class="row">

    <!-- MUltiple BAR Vertical GOOGLE -->
    <div class="col-sm-6 mt-4 mx-auto bar_Qtd">
        <div class="card">
            <div class="card-body p-2">
                <div id="bar_Qtd" style="width: 100%; height: 420px;"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 mt-4 mx-auto bar_Valor">
        <div class="card">
            <div class="card-body p-2">
                <div id="bar_Valor" style="width: 100%; height: 420px;"></div>
            </div>
        </div>
    </div>
    @if (Auth::user()->role != "franqueado")
    <div class="col-sm-12 mt-4 mx-auto bar_Valor_todos">
        <div class="card">
            <div class="card-body p-2">
                <div id="bar_Valor_todos" style="width: 100%; height: 420px;"></div>
            </div>
        </div>
    </div>


    <div class="col-sm-6 mt-4 mx-auto">
        <div class="card">
            <div class="card-body p-2">
            <div id="pedMensal" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 mt-4 mx-auto">
        <div class="card">
            <div class="card-body p-2">
                <div id="pedGeral" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
    </div>


    <div class="col-sm-12 mt-4 mx-auto bar_Qtd_todos">
        <div class="card">
            <div class="card-body p-2">
                <div id="bar_Qtd_todos" style="width: 100%; height: 420px;"></div>
            </div>
        </div>
    </div>
    @endif



    <!-- BAR Vertical Simples / DIA - QTD -->
    <div class="col-sm-12 col-md-12 mt-4 weekChart">
        <div class="card">
            <div class="card-body py-4">
                <div style="width: 90%; margin: auto;">
                    <canvas id="weekChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @if (Auth::user()->role != "franqueado")
    <div class="col-sm-12 col-md-12 mt-4 weekChartTodos">
        <div class="card">
            <div class="card-body py-4">
                <div style="width: 90%; margin: auto;">
                    <canvas id="weekChartTodos"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-sm-12 mt-4 mx-auto">
        <div class="card">
            <div class="card-body p-2">
                <div id="line_chart" style="width: 100%; height: 420px"></div>
            </div>
        </div>
    </div>
    @if (Auth::user()->role != "franqueado")
    <div class="col-sm-12 col-md-12 mt-4 ticket_medio_todos">
        <div class="card">
            <div class="card-body py-4">
                <div style="width: 90%; margin: auto;">
                    <canvas id="ticket_medio_todos"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 mt-4 weekChart2">
        <div class="card">
            <div class="card-body py-4">
                <!-- Dentro do seu arquivo Blade -->
                <label for="">Data:</label>
                <input type="date" name="data" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="data_franquia">

                <div style="width: 90%; margin: auto;">
                    <canvas id="weekChart2"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif





    <!-- BAR Vertical Simples / MES R$-->




    <!-- DONUT 3D GOOGLE -->
    <div class="col-sm-12 mt-4">
        <div class="card">
            <div class="card-body py-1 d-flex align-items-center mx-auto">

                <div id="donutchart" style="width: 100%; height: 420px;"></div>
            </div>
        </div>
    </div>


    <!-- Grafico de Linhas -->



    <!-- BAR Vertical Simples / MES R$-->
    <div class="col-sm-12 col-md-12 mx-auto mt-4">
        <div class="card">
            <div class="card-body p-5">
                <div style="width: 80%; margin: auto;">
                    <canvas id="barChart2"></canvas>
                </div>
            </div>
        </div>
    </div>





</div>