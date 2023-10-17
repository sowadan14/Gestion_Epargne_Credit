@extends('layouts.master')
@section('content')
<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
	<div class="EnteteContent">
		<div class="row">

			<div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
				<i class="menu-icon icon-institution bigger-130"></i> Caisses/Banques

			</div>
		</div>
		<hr  class="hrEntete">
		<!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->

		@if ($message = Session::get('success'))
		<div class="alert alert-success">
			<p>{{ $message }}</p>
		</div>
		@endif

		@if ($message = Session::get('danger'))
		<div class="alert alert-danger">
			<p>{{ $message }}</p>
		</div>
		@endif
		<!-- <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Product</a> -->
		<div class="row">
			<div class="col-lg-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered table-stripped data-table">
								<thead>
									<tr>
										<th hidden>N°</th>
										<th class="text-center">
											{{ Form::checkbox('SelectedAll[]',false,false, array('class' =>
											'checkbox','id' => 'SelectedAll')) }}
										</th>
										<th>Code</th>
										<th>Libellé</th>
										<th class="text-right">Solde</th>
										<th class="text-center">Actions</th>
									</tr>
								</thead>
								<tbody>
									@if(count($data) > 0)

									@foreach($data as $row)

									<tr>
										<!-- <td><img src="{{ asset('images/' . $row->student_image) }}" width="75" /></td> -->
										<td hidden>{{ $row->id }}</td>
										<td class="text-center">{{ Form::checkbox('Selected[]',$row->id, false)}}</td>
										<td>{{ $row->Code }}</td>
										<td>{{ $row->Libelle }}</td>
										<td class="text-right">{{number_format($row->Solde,0,',',' ') }} </td>
										<td class="text-center">

											<div class="btn-group dropleft" style="float: center;">
												<button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle blue" aria-expanded="false">
													<span style="font-weight:bold;">
														Actions
														<span class="ace-icon fa fa-caret-down icon-on-right"></span>
													</span>

												</button>


												<ul class="dropleft dropdown-menu dropdown-inverse">
													<li>
														<p style="min-width:250px;margin:0 0 0px;">
															<a href="{{ route('caissebanque.details',$row->id) }}" style="padding-bottom:4px;" class="btn btn-white btn-xs bolder"><span class="ace-icon fa fa-eye dark"></span> Aperçu</a>

														
														</p>
													</li>
												</ul>
											</div>

										</td>
									</tr>

									@endforeach

									@else
									<tr>
										<td colspan="3" class="text-center">No Data Found</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<script type="text/javascript">
	$(function() {



		/*------------------------------------------
		 --------------------------------------------
		 Pass Header Token
		 --------------------------------------------
		 --------------------------------------------*/
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		/*------------------------------------------
		--------------------------------------------
		Render DataTable
		--------------------------------------------
		--------------------------------------------*/
		var table = $('.data-table').DataTable({
			"language": {
				"thousands": ' ',
				"decimal": ",",
				"thousands": " ",
				"sProcessing": "Traitement en cours...",
				"sSearch": "Rechercher&nbsp;:",
				"sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
				"sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
				"sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
				"sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				"sInfoPostFix": "",
				"sLoadingRecords": "Chargement en cours...",
				"sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
				"sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
				"oPaginate": {
					"sFirst": "Premier",
					"sPrevious": "Pr&eacute;c&eacute;dent",
					"sNext": "Suivant",
					"sLast": "Dernier"
				},
				"oAria": {
					"sSortAscending": ": activer pour trier la colonne par ordre croissant",
					"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
				}
			},
			processing: true,
			ordering: false,
			// serverSide: true,
			"order": [
				[0, 'desc']
			],
			// ajax: "{{ route('comptes.index') }}",
			// columns: [
			//     // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
			//     {data: 'id', name: 'id'},
			//     {data: 'Libelle', name: 'Libelle'},
			//     {data: 'action', name: 'action', orderable: false, searchable: false,class:'text-center'},
			// ]
		});
	});
</script>
@endsection