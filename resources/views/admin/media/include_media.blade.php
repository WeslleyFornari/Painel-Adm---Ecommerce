@section('pre-assets')
<style>
	.list-files {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	.list-files .lista-arquivos li {
		display: inline-block;
		vertical-align: top;
		text-align: center;
		border: 1px solid #ededed;
		padding: 5px;
		border-radius: 4px;
		margin-bottom: 5px;
		width: 14%;
		min-width: 140px;
	}
	li.active a {
		color: #fff;
	}
	.list-files li img {
		width: auto;
		height: auto;
		max-height: 85px;
		object-fit: cover;
	}
	.list-files li:hover {
		background: #ededed;
		transition: 0.2s all linear;
	}
	.list-files li a {
		display: block;
		height: 85px;
		overflow: hidden;
		line-height: 85px;
		vertical-align: middle;
		margin: 0 auto;
		color: #666;
		background: #f7f7f7;
	}
	.newFolter {
		float: right;
		position: relative;
	}
	.box-newFolder {
		display: none;
		z-index: 100;
	}
	.container-upload {
    background: rgba(17, 74, 153, .26);
    border: 1px solid #114a99;
    margin-bottom: 20px;
    text-align: center;
    font-size: 1.5em;
    position: relative;
    padding: 5px
}

.container-upload input.uploadArquivos{
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    z-index: 1;
    cursor: pointer
}
.btn-close{
	color: #333;
	float: right;
}
</style>
<!-- /.col -->
<section class="content-header m-bottom-md">
	<h5 class="modal-title float-start">Media Manager</h5>
	<button type="button" class="btn-close float-end text-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
	<div class="clearfix"></div>

	<div id="errorContainer" style="color: red"></div>
</section>
<div class="content row">
<div class="col-3">

<div class="card card-primary">

	<!-- Default card contents -->

	





<div id="lista-pastas" style="max-height: calc(100vh - 110px); overflow-y: auto;">
@include('admin.media._pastas')
</div>

</div>

</div>
	<div class="col-9 pull-right">
		<div class="card card-default">
			
			<div class="card-body">
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							
							<div class="container-upload m-0">
								
								<div class="carregandoForm" style="display: none"><i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span></div>
								<input type="file" id="uploadArquivos" class="uploadArquivos" multiple="" name="file">
								<div><i class="fa fa-cloud-upload" aria-hidden="true"></i> UPLOAD FILE</div>
							</div>
						</div>
						<div class="alert alert-success p-all-xs" role="alert" style="display: none"></div>
					</div>
				<div class="col-6">
					<input placeholder="Pesquisar" onkeyup="searchUser('listArquivos','filterlistFiles')" id="filterlistFiles" class="form-control ">
					</div>
				</div>
				<hr>
				<div class="row" >
					<form action="" id="formFiles">
						@csrf
						<input type="hidden" name="folder" value="{{$folderAtual}}">
						<input type="hidden" name="folder_parent" value="{{$folder_parent}}">
						<div class="col-12">
							<ul class="list-files" id="listArquivos">
							@include('admin.media._list-files')
							</ul>
						</div>
					</form>
				</div>
				<div class="row  m-top-xs " id="row">
					<div class="col">
						<input type="checkbox" name="checkAll" id=""> Marcar todos
					</div>
					<!--
					<div class="col-12 pull-right text-center">
						<a href="" class="btn btn-danger btn-sm actionRemove">Remover</a>
						<a href="" class="btn btn-primary btn-sm usarImage" data-alvo="destaque">Usar Imagem em Destaque</a>
						<a href="" class="btn btn-primary btn-sm usarImage" data-alvo="galeria">Usar Imagem em Galeria de fotos</a>
					</div>
					-->
				</div>
			
			</div>
			<div class="modal-footer">
				<a href="" class="btn btn-danger actionRemove">Remover</a>
				<a href="" class="btn btn-primary  usarImage">Inserir</a>
			</div>
		</div>
	</div>
</div>
