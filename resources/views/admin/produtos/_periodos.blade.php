@if ($periodos && $periodos->count() != 0)
    @foreach ($periodos as $periodo)
        <span class="titulo"> Valor Período de {{$periodo->dias}} dias: *</span>
        <input type="hidden" name="valores_periodos[id_periodo][]" value="{{$periodo->id}}">
        @if ($produto?->valor_periodo($periodo->id))
        <input type="text" name="valores_periodos[valor][{{$periodo->id}}]" value="{{$produto?->valor_periodo($periodo->id)->valor_periodo}}" class="form-control moneyMask"  id="valor_periodo-{{$periodo->id}}">  
        @else
        <input type="text" name="valores_periodos[valor][{{$periodo->id}}]" value="" class="form-control moneyMask" id="valor_periodo-{{$periodo->id}}">  
        @endif
    @endforeach
@else
    @foreach (periodos_sem_franquia() as $periodo)
        <span class="titulo"> Valor Período de {{$periodo->dias}} dias: *</span>
        <input type="hidden" name="valores_periodos[id_periodo][]" value="{{$periodo->id}}">
        @if ($produto?->valor_periodo($periodo->id))
        <input type="text" name="valores_periodos[valor][{{$periodo->id}}]" value="{{$produto?->valor_periodo($periodo->id)->valor_periodo}}" class="form-control moneyMask" id="valor_periodo-{{$periodo->id}}">  
        @else
        <input type="text" name="valores_periodos[valor][{{$periodo->id}}]" value="" class="form-control moneyMask" id="valor_periodo-{{$periodo->id}}">  
        @endif
    @endforeach
@endif