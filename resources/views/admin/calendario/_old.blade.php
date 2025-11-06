<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- TOKEN -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
  {{config('APP_NAME')}}
  </title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/40b7169917.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v5.15.4/css/all.css">  
  <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
 <link rel="stylesheet" href="{{asset('css/toggle_Switch02.css')}}">

  
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('build/assets/app-c2a4d485.css') }}">  

    <script src="{{ asset('lib/dhtmlxScheduler/dhtmlxscheduler.js') }}"></script>
    <script src="{{ asset('lib/dhtmlxScheduler/ext/dhtmlxscheduler_limit.js') }}"></script>
    <script src="{{ asset('lib/dhtmlxScheduler/ext/dhtmlxscheduler_collision.js') }}"></script>
    <script src="{{ asset('lib/dhtmlxScheduler/ext/dhtmlxscheduler_timeline.js') }}"></script>
    <script src="{{ asset('lib/dhtmlxScheduler/ext/dhtmlxscheduler_editors.js') }}"></script>
    <script src="{{ asset('lib/dhtmlxScheduler/ext/dhtmlxscheduler_minical.js') }}"></script>
    <script src="{{ asset('lib/dhtmlxScheduler/ext/dhtmlxscheduler_tooltip.js') }}"></script>

    <link rel='stylesheet' href="{{ asset('lib/dhtmlxScheduler/dhtmlxscheduler_flat.css') }}">

	<style>
		.navbar-vertical .navbar-nav .nav-item .nav-link .icon i {
		color: #1D3857 !important;
		font-size: 15px;
		}

		.cabecalho{
		font-weight: 700;
		}

		nav .justify-between{
			display: none;
		}

		nav .hidden .relative a svg{
			width: 2% !important;
		}
		nav .hidden .relative span svg{
			width: 2% !important;
		}

		.hidden .relative span span{
			background: #ed3237 !important;
			color: #fff;
		}

		.hidden .relative span .rounded-l-md{
			background: #fff !important;
			color: #67748E;
		}

		.hidden .relative span .rounded-r-md{
			background: #fff !important;
			color: #67748E;
		}

		.icon i{
			font-size: 15px;
			color: #ed3237 !important;
		}

		.navbar-vertical .navbar-nav > .nav-item .nav-link.active .icon i{
		color: #fff !important;
		}

		@media (max-width: 950px) {
			nav .hidden .relative a svg{
				width: 10% !important;
			}
			nav .hidden .relative span svg{
				width: 10% !important;
			}

			
		}

		.modal {
			display: none; /* Inicialmente oculto */
			position: fixed;
			z-index: 1050; /* Acima do conteúdo */
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			overflow: auto;
			background-color: rgba(0, 0, 0, 0.5); /* Fundo escuro */
		}
		.modal-dialog {
			position: relative;
			margin: 2% auto;
		}
		.modal-content {
			background-color: #fff;
			border: 1px solid #888;
			border-radius: 5px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}


	</style>
    <style>
        html, body {
			margin: 0;
			padding: 0;
			height: 100%;
			overflow: hidden;
		}


		.dhx_cal_container #room_filter:focus{
			outline: 1px solid #52daff;
		}

		.timeline-cell-inner {
			height: 100%;
			width: 100%;
			table-layout: fixed;
		}

		.timeline-cell-inner td {
			border-left: 1px solid #cecece;
		}

		.dhx_section_time select {
			display: none;
		}

		.timeline_weekend {
			background-color: #FFF9C4;
		}

		.timeline_item_cell {
			float: left;
			width: 50%;
			height: 100% !important;
			font-size: 14px;
			text-align: center;
			line-height: 50px;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.timeline_item_cett {
			float: left;
			width: 48%;
			height: 100% !important;
			font-size: 14px;
			text-align: center;
			line-height: 50px;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.timeline_item_cepp {
			float: left;
			width: 48%;
			height: 100% !important;
			font-size: 14px;
			text-align: center;
			line-height: 50px;
		}

		.room_status {
			position: relative;
		}

		.timeline_item_separator {
			float: left;
			background-color: #CECECE;
			width: 1px;
			height: 100% !important;
		}

		.room_status_indicator {
			position: absolute;
			background-color: red;
			left: 0;
			top: 0;
			right: 95%;
			bottom: 0;
		}

		.room_status_indicator_1 {
			background-color: #4CAF50;
		}

		.room_status_indicator_2 {
			background-color: red;
		}

		.room_status_indicator_3 {
			background-color: #FFA000;
		}

		.dhx_cal_event_line {
			background-color: #FFB74D !important;
		}

		.event_1 {
			background-color: #FFB74D !important;
		}

		.event_2 {
			background-color: #9CCC65 !important;
		}

		.event_3 {
			background-color: #40C4FF !important;
		}

		.event_4 {
			background-color: #BDBDBD !important;
		}

		.booking_status, .booking_paid {
			position: absolute;
			right: 5px;
		}

		.booking_status {
			top: 2px;
		}

		.booking_paid {
			bottom: 2px;
		}

		.dhx_cal_event_line:hover .booking-option {
			background: none !important;
		}

		.dhx_cal_header .dhx_scale_bar {
			line-height: 26px;
			color: black;
		}

		.dhx_section_time select {
			display: none
		}

		.dhx_mini_calendar .dhx_year_week,
		.dhx_mini_calendar .dhx_scale_bar {
			height: 30px !important;
		}

		.dhx_cal_light_wide .dhx_section_time {
			text-align: left;
		}

		.dhx_cal_light_wide .dhx_section_time > input:first-child {
			margin-left: 10px;
		}

		.dhx_cal_light_wide .dhx_section_time input {
			border: 1px solid #aeaeae;
			padding-left: 5px;
		}

		.dhx_cal_light_wide .dhx_readonly {
			padding: 3px;
		}

		.collection_label .timeline_item_cell {
			line-height: 60px;
		}

		.dhx_cal_radio label, .dhx_cal_radio input {
			vertical-align: middle;
		}

		.dhx_cal_radio input {
			margin-left: 10px;
			margin-right: 2px;
		}

		.dhx_cal_radio input:first-child {
			margin-left: 5px;
		}

		.dhx_cal_radio {
			line-height: 19px;
		}

		.dhtmlXTooltip.tooltip {
			color: #4d4d4d;
			font-size: 15px;
			line-height: 140%;
		}

		.dhx_matrix_scell{
			display: flex;
		}
    </style>
</head>
<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    
    @include('layouts._aside')
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
   
  <div class="justify-content-end pt-4">
   
    <!-- <div class="col-9">
        <h4>{{Route::current()->getName()}}</h4>
    </div> -->
    
      <div class="col-12 text-end pe-5">
        @if(Auth::check())
          <form method="POST" action="{{ route('logout') }}">
          @csrf
            <button class="btn btn-secondary" type="submit"><i class="fas fa-right-from-bracket mx-1"></i>Sair</button>
        
          </form>
        @endif
      </div>

  </div>
  <div class="row mb-3">
	<div class="col-5 d-flex" style="height: 35px;">
		@if(Auth::user()->role != 'franqueado')
		<p class="me-3">Franquia:</p>
		<select class="form-select me-3 p-0 ps-2" id="franquiaid">
			<option value="todos" selected>Todos</option>
			@foreach($selecionarFranquia as $franquias)
				<option value="{{$franquias->id}}">{{ $franquias->nome_franquia}}</option>
			@endforeach
		</select>
		@else
		<input type="hidden" id="franquiaid" value="{{ Auth::user()->id_franquia }}">
		@endif
	</div>
	<div class="col-5 d-flex" style="height: 35px;">
		<p class="me-3">Produtos: </p>
		<select class="form-select p-0 ps-2" id="produtoid">
			<option value="todos" selected>Todos</option>
			@foreach($selecionarProduto as $produto)
				<option value="{{$produto->id}}">{{ $produto->nome}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-2" style="height: 35px;">
		<button type="button" class="btn btn-primary exemplo" id="abrirModal">
		Adicionar
		</button>
	</div>
  </div>
      <div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
          <div class="dhx_cal_navline">
              <div class="dhx_cal_prev_button">&nbsp;</div>
              <div class="dhx_cal_next_button">&nbsp;</div>
              <div class="dhx_cal_today_button"></div>
              <div class="dhx_cal_date"></div>
          </div>
          <div class="dhx_cal_header">
          </div>
          <div class="dhx_cal_data">
          </div>     
      </div>

<div id="meuModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Item Estoque</h5>
                <button type="button" class="close" onclick="fecharModal()">&times;</button>
            </div>
            <div class="modal-body">
				<form action="{{ route('admin.calendarios.store') }}" id="formStore" method="POST" enctype="multipart/form-data">
                    @csrf
					<div class="row mt-3">
						<div class="form-group col-sm-12">
							<div>Pedido</div>
							<select class="form-select" name="pedido" id="pedido">
								<option value="">Selecione o Pedido</option>
								@foreach($pedidos as $pedido)
									<option value="{{ $pedido->id }}">{{ $pedido->numero }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-sm-12" id="col-produtos">
							@include('admin.calendario._produtos')
						</div>
						<div class="form-group col-sm-12" id="col-estoques">
							@include('admin.calendario._estoques')
						</div>
						<div class="form-group col-sm-12 row" id="col-datas">
							@include('admin.calendario._datas')
						</div>
					</div>
			</div>
            <div class="modal-footer">
				<div class="row">
					<div class="col text-end">
						<button type="submit" class="btn btn-success">Salvar</button>
					</div>
					<button type="button" class="btn" onclick="fecharModal()">Fechar</button>
				</div>
            </div>
			</form>
        </div>
    </div>
</div>

	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

document.addEventListener('DOMContentLoaded', function() {
    init();
});
(function () {
	scheduler.locale.labels.section_text = 'Name';
	scheduler.locale.labels.section_room = 'estoque';
	scheduler.locale.labels.section_time = 'Time';
	scheduler.xy.scale_height = 30;
	scheduler.config.details_on_create = true;
	scheduler.config.details_on_dblclick = false;
	scheduler.config.prevent_cache = true;
	scheduler.config.show_loading = true;
	scheduler.config.xml_date = "%Y-%m-%d %H:%i";

	var roomsArr = scheduler.serverList("estoque");
	var roomTypesArr = scheduler.serverList("produto");
	var roomtitulo = scheduler.serverList("pedido");

	scheduler.config.lightbox.sections = [
		{map_to: "text", name: "text", type: "textarea", height: 24},
		{map_to: "estoque", name: "estoque", type: "select", options: scheduler.serverList("currentRooms")},
		{map_to: "time", name: "time", type: "calendar_time"}
	];

	scheduler.locale.labels.timeline_tab = 'Timeline';

	scheduler.createTimelineView({
		fit_events: true,
		name: "timeline",
		y_property: "estoque",
		render: 'bar',
		x_unit: "day",
		x_date: "%d",
		x_size: 45,
		dy: 55,
		event_dy: 50,
		dx: 350,
		section_autoheight: false,
		round_position: true,

		y_unit: scheduler.serverList("currentRooms"),
		second_scale: {
			x_unit: "month",
			x_date: "%F %Y"
		}
	});

	function findInArray(array, key) {
		for (var i = 0; i < array.length; i++) {
			if (key == array[i].key)
				return array[i];
		}
		return null;
	}

	function getRoomType(key) {
		return findInArray(roomTypesArr, key).label;

	}
	function getRoomTitulo(key) {
		return findInArray(roomtitulo, key).label;
	}

	function getRoomStatus(key) {
		return findInArray(roomStatusesArr, key);
	}

	function getRoom(key) {
		return findInArray(roomsArr, key);
	}

	scheduler.templates.timeline_scale_label = function (key, label, section) {
		return ["<div class='timeline_item_separator'></div>",
			"<div class='timeline_item_cell'>" + label + "</div>",
			"<div class='timeline_item_separator'></div>",
			"<div class='timeline_item_cepp'>" + getRoomType(section.type) + "</div>",
			"</div>"].join("");
	};

	scheduler.date.timeline_start = scheduler.date.month_start;
	scheduler.date.add_timeline = function (date, step) {
		return scheduler.date.add(date, step, "month");
	};

	scheduler.attachEvent("onBeforeViewChange", function (old_mode, old_date, mode, date) {
		var year = date.getFullYear();
		var month = (date.getMonth() + 1);
		var d = new Date(year, month, 0);
		var daysInMonth = d.getDate();
		scheduler.matrix["timeline"].x_size = daysInMonth;
		return true;
	});

	var eventDateFormat = scheduler.date.date_to_str("%d %M %Y");
	scheduler.templates.event_bar_text = function (start, end, event) {
		// var paidStatus = getPaidStatus(event.is_paid);
		var startDate = eventDateFormat(event.start_date);
		var endDate = eventDateFormat(event.end_date);
		return [getRoomTitulo(event.pedido) + "<br />",
			// startDate + " - " + endDate,
			//  "</div>"
		].join("");
	};

	scheduler.templates.tooltip_text = function (start, end, event) {
		var estoque = getRoom(event.estoque) || {label: ""};

		var html = [];
		html.push("Pedido: <b>" + getRoomTitulo(event.pedido) + "</b>");
		html.push("Estoque: <b>" + estoque.label + "</b>");
		html.push("Data Retirada: <b>" + eventDateFormat(start) + "</b>");
		html.push("Data Devolução: <b>" + eventDateFormat(end) + "</b>");
		return html.join("<br>")
	};

	scheduler.templates.lightbox_header = function (start, end, ev) {
		var formatFunc = scheduler.date.date_to_str('%d.%m.%Y');
		return formatFunc(start) + " - " + formatFunc(end);
	};

	scheduler.attachEvent("onEventCollision", function (ev, evs) {
		for (var i = 0; i < evs.length; i++) {
			if (ev.estoque != evs[i].estoque) continue;
			dhtmlx.message({
				type: "error",
				text: "This estoque is already booked for this date."
			});
		}
		return true;
	});

	scheduler.attachEvent('onEventCreated', function (event_id) {
		var ev = scheduler.getEvent(event_id);
		ev.status = 1;
		ev.is_paid = false;
		ev.text = 'new booking';
	});

	scheduler.addMarkedTimespan({days: [0, 6], zones: "fullday", css: "timeline_weekend"});

	window.updateSections = function updateSections(value) {
		var currentRoomsArr = [];
		if (value == 'all') {
			scheduler.updateCollection("currentRooms", roomsArr.slice());
			return
		}
		for (var i = 0; i < roomsArr.length; i++) {
			if (value == roomsArr[i].type) {
				currentRoomsArr.push(roomsArr[i]);
			}
		}
		scheduler.updateCollection("currentRooms", currentRoomsArr);
	};

	scheduler.attachEvent("onXLE", function () {
		updateSections("all");

		var select = document.getElementById("room_filter");
		var selectHTML = ["<option value='all'>All</option>"];
		for (var i = 1; i < roomTypesArr.length + 1; i++) {
			selectHTML.push("<option value='" + i + "'>" + getRoomType(i) + "</option>");
		}
		select.innerHTML = selectHTML.join("");
	});

	scheduler.attachEvent("onEventSave", function (id, ev, is_new) {
		if (!ev.text) {
			dhtmlx.alert("Text must not be empty");
			return false;
		}
		return true;
	});

})();

function init() {
	var id = $("#franquiaid").val();
	var idProduto = $("#produtoid").val();
	var url = '{{ route("admin.calendarios.data", ["id" => ":id", "idProduto" => ":idProduto"]) }}';
    url = url.replace(':id', id).replace(':idProduto', idProduto);
	console.log(id);
	const ano = new Date().getFullYear();
	const mes = new Date().getMonth();
	scheduler.init('scheduler_here', new Date(ano, mes, 1), "timeline");
	scheduler.load(url, "json");
	window.dp = new dataProcessor(url);
	dp.init(scheduler);


	(function () {
		var element = document.getElementById("scheduler_here");
		var top = scheduler.xy.nav_height + 1 + 1;
		var height = scheduler.xy.scale_height;
		var width = scheduler.matrix.timeline.dx;
		var header = document.createElement("div");
		header.className = "collection_label";
		header.style.position = "absolute";
		header.style.top = top + "px";
		header.style.width = width + "px";
		header.style.height = height + "px";

		var descriptionHTML = "<div class='timeline_item_separator'></div>" +
			"<div class='timeline_item_cell'>Código</div>" +
			"<div class='timeline_item_separator'></div>" +
			"<div class='timeline_item_cett'>Produtos</div>";
		header.innerHTML = descriptionHTML;
		element.appendChild(header);
	})();
}

$("body").on('change', '#franquiaid', function (e) {
    e.preventDefault();
    init();
});
$("body").on('change', '#pedido', function (e) {
    e.preventDefault();
    var id_pedido = $(this).val();

	var url = '{{ route("admin.calendarios.produtos", ["id" => ":id"]) }}';
    url = url.replace(':id', id_pedido);

	$.get(url, function (data) {

        $("#col-produtos").html(data);
       
    })

});
$("body").on('change', '#produtos', function (e) {
    e.preventDefault();
    var id_produto = $(this).val();
    var id_pedido = $('#pedido').val();

	var url = '{{ route("admin.calendarios.estoques", ["id" => ":id"]) }}';
    url = url.replace(':id', id_produto);

	$.get(url, function (data) {

        $("#col-estoques").html(data);
       
    })

	var url2 = '{{ route("admin.calendarios.datas", ["id" => ":id", "id_produto" => ":id_produto"]) }}';
    url2 = url2.replace(':id', id_pedido).replace(':id_produto', id_produto);

	$.get(url2, function (data) {

        $("#col-datas").html(data);
       
    })

});
$("body").on('change', '#produtoid', function (e) {
    e.preventDefault();
    init();
});
function mostrarModal() {
    var modal = document.getElementById('meuModal');
    modal.style.display = "block"; 
    modal.querySelector('.modal-dialog').focus();
}

function fecharModal() {
    var modal = document.getElementById('meuModal');
    modal.style.display = "none";
    modal.style.display = "none";

	$("#formStore")[0].reset();
}
document.getElementById('abrirModal').addEventListener('click', mostrarModal);

$("#formStore").submit(function (e) {
    e.preventDefault();
    $("span.error").remove()
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            console.log(data);
            if (data === "editado") {
                swal({
                    title: "Parabéns",
                    text: "Editado com sucesso!.",
                    icon: "success",
                }).then(function() {
                    location.reload();
                });
            } else {
                swal({
                    title: "Parabéns",
                    text: "Cadastro realizado com sucesso!.",
                    icon: "success",
                }).then(function() {
					var modal = document.getElementById('meuModal');
					modal.style.display = "none";

                    location.reload();
                });
            }
            $("#formStore")[0].reset();
            $(".disabled").remove();
        },
        error: function (err) {
            console.log(err);

            if (err.status == 422) { // when status code is 422, it's a validation issue
                console.log(err.responseJSON);
                $('#success_message').fadeIn().html(err.responseJSON.message);
                // you can loop through the errors object and show it to the user
                console.warn(err.responseJSON.errors);
                // display errors on each form field
                $.each(err.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.after($('<span class="error" style="color: red;">' + error[0] +
                        '</span>'));
                });
            }
        }
    })
})



</script>

</body>
</html>