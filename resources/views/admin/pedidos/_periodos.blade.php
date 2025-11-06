@if ($franquia->periodos->count() != 0)
    @foreach ($franquia->periodos as $periodo)
        <option value="{{$periodo->id}}">{{$periodo->dias}} dias</option>
    @endforeach
@else
    @foreach (periodos_sem_franquia() as $periodo)
        <option value="{{$periodo->id}}">{{$periodo->dias}} dias</option>
    @endforeach
@endif