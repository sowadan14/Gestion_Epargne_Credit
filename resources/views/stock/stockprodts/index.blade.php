@extends('layouts.master')
@section('content')
<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
	<div class="EnteteContent">
		<div class="row">

			<div class="col-sm-12 col-xs-12 col-md-6 col-lg-6 styleEntete">
				<i class="menu-icon icon-product-hunt   bigger-130"></i> Stock produit

			</div>
		</div>
		<hr class="hrEntete">
		<!-- <p><button class="btn btn-sm btn-primary"><span class=" glyphicon glyphicon-plus"></span> Nouveau</button></p> -->


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
										<th  class="text-center">Image</th>
										<th>Code</th>
										<th>Libellé</th>
										<th>Code barre</th>
										<th>Famille</th>
										<th class="text-right">CUMP</th>
										<th>Stock</th>
										<th>Valeur en stock</th>
										<th>Détails</th>
									</tr>
								</thead>
								<tbody>
									@if(count($collection) > 0)

									@foreach($collection as $row)

									<tr>
										<td hidden>{{ $row['id'] }}</td>
										<td class="text-center">{{ Form::checkbox('Selected[]',$row['id'], false)}}</td>
										<td  class="text-center">
											@if ($row['Prod_logo'])
											<img id="blah" src="{{ asset('storage/images/'.$row['Prod_logo']) }}" alt="User photo" width="20px" height="20px" style="border-radius:100px;" />
											@endif
										</td>
										<td>{{ $row['Code'] }}</td>
										<td>{{ $row['Libelle'] }}</td>
										<td>{{ $row['CodeBar'] }}</td>
										<td>{{ isset($row['categproduit']) ? $row['categproduit']:''}}</td>
										<td class="text-right">{{ number_format($row['CUMP'],0,',',' ')  }}</td>
										<td>{{number_format($row['Stock'],0,',',' ')  }} {{ $row['Gestion'] }}(s)</td>
										<td>{{number_format($row['ValueStock'],0,',',' ')  }} {{ auth()->user()->entreprise->Devise}}</td>
										<td>
											@foreach($row['Details'] as $unite)
											<span><strong>{{$unite->Nom}}:</strong> {{number_format($unite->pivot->Qte,0,',',' ') }}</span><br />
											@endforeach
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
<div hidden>
	{!! Form::open(['method' => 'DELETE','style'=>'display:inline','id'=>'SubmitForm']) !!}

	{!! Form::close() !!}

</div>

<script type="text/javascript">
	$('.show_confirm').click(function(event) {
		var id = $(this).data('id');
		var name = $(this).attr('role');
		$("#SubmitForm").attr('action', '/config/prodts/' + id);

		event.preventDefault();
		swal({
				title: `Etes-vous sûr de vouloir supprimer : ` + name + '?',
				text: "Il n'y a plus de retour en arrière.",
				icon: "error",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$("#SubmitForm").submit();
				}
			});
	});
</script>



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
			// ajax: "{{ route('prodts.index') }}",
			// columns: [
			//     // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
			//     {data: 'id', name: 'id'},
			//     {data: 'Nom', name: 'Nom'},
			//     {data: 'action', name: 'action', orderable: false, searchable: false,class:'text-center'},
			// ]
		});


	});
</script>
@endsection