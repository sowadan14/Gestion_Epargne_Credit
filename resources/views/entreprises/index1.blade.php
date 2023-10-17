
@extends('layouts.master')
@section('content')
<style tyle="css/text">
.label {
    font-weight: 900;
    font-size: 12px;
    text-align:left;
}

label {
    font-weight: 900;
}

.form-control {
    width: 100%;
    height: 30px;
}

.label-lg {
    padding: 0.2em 0.6em 0.4em;
    font-size: 13px;
    line-height: 1.1;
    height: 24px;
}
</style>



@can('createentreprise')
<p>
<a class="btn btn-md btn-round btn-primary" href="javascript:void(0)" id="addentreprise"><span class=" glyphicon glyphicon-plus"></span> Nouveau</a>
</p>

@endcan
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
                <th>N°</th>
                <th>Libellé</th>
                <th>Email</th>
                <th  class="text-center">Date Création</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if (is_countable($data) && count($data) > 0)

				@foreach($data as $row)

					<tr>
						<!-- <td><img src="{{ asset('images/' . $row->student_image) }}" width="75" /></td> -->
						<td>{{ $row->id }}</td>
						<td>{{ $row->Libelle }}</td>
            <td>{{ $row->Email }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($row->DateCreation)->format('d/m/Y')}} </td>
            <td>{{ $row->Telephone }}</td>
            <td>{{ $row->Adresse }}</td>
						<!-- <td>{{ $row->student_gender }}</td> -->
						<td class="text-center">
           
							<a href="javascript:void(0)" data-toggle="tooltip"  data-id="{{ $row->id }}" data-original-title="Details" class="btn btn-default btn-sm showEntreprise" title="Details"><span class="blue"><i class="ace-icon fa fa-eye"></i></span></a>
              @can('editentreprise')
              <a href="javascript:void(0)" data-toggle="tooltip"  data-id="{{ $row->id }}" data-original-title="Modifier" class="btn btn-warning btn-sm editEntreprise"  title="Modifier"><span class="white"><i class="glyphicon glyphicon-edit"></i></span></a>
              @endcan
              @can('deleteentreprise')
              <a href="javascript:void(0)" data-toggle="tooltip"  role="{{ $row->Libelle }}"  data-id="{{ $row->id }}" data-original-title="Supprimer" class="btn btn-danger btn-sm deleteEntreprise"  title="Supprimer"><span class="white"><i class="ace-icon fa fa-trash-o bigger-120"></i></span></a>
              @endcan
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



<div class="modal fade" id="ajaxModel"  data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
            <div class="PartianDiv">
             
            </div>
               
              </div>
            <div class="modal-footer">
            <div class="col-sm-12">
            <button type="button" class="btn  btn-default btn-round float-lg-left Closer btn-md" data-dismiss="modal" style="float:left;"><i class="ace-icon fa fa-times"></i> Fermer</button>
           <button type="button" id="CreateForm" value="Create" class="btn btn-primary Saver btn-md btn-round" style="background-color:orange;" >
           <i class="ace-icon fa fa-floppy-o bigger-120"></i> Enregistrer
            </button>
                    </div>
                   
                    </div>
      </div>
  </div>
</div>

<div class="modal fade" id="deleteAjaxModel"  data-backdrop="false" data-keyboard="false">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title" id="deleteModelHeading"></h4>
          </div>
          <form action="" id="deleteForm" name="deleteForm" method="POST" class="form-horizontal">

          <div class="modal-body">
              {{method_field('DELETE')}}
              {{csrf_field()}}
              <!-- <input type='hidden' name="_method"></input> -->
                 <div class="form-group">
                  <input type="hidden" name="deleteentrepriseid" id="deleteentrepriseid">
                  <div class="col-sm-12 forDelete" style="color:red;font-weight: bold;">
                   
                  </div>
                </div>
          </div>
          <div class="modal-footer">
            <div class="col-sm-12">
              <button type="button" class="btn  btn-default btn-round float-lg-left btn-md" data-dismiss="modal" style="float:left;"><i class="ace-icon fa fa-times"></i> Fermer</button>
             <button type="submit" id="DeleteBtn" value="Delete" class="btn btn-danger Saver btn-md btn-round">
             <i class="ace-icon fa fa-trash-o bigger-120"></i> Supprimer
              </button>
                      </div>
                 
</div>
</form>

      </div>
  </div>
</div>

<script type="text/javascript">


$(document).ready(function () {
   
      $(document).on('change', 'input:checkbox', function () {
      var id=$(this).attr('role');
            $("input:checkbox."+id).prop('checked', $(this).prop("checked"));
        });
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
        // serverSide: true,
        "order": [[0, 'desc']],
        // ajax: "{{ route('entreprises.index') }}",
        // columns: [
        //     // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        //     {data: 'id', name: 'id'},
        //     {data: 'Libelle', name: 'Libelle'},
        //     {data: 'action', name: 'action', orderable: false, searchable: false,class:'text-center'},
        // ]
    });
      
    /*------------------------------------------
    --------------------------------------------
    Click to Button
    --------------------------------------------
    --------------------------------------------*/
    // $('#addentreprise').click(function () {
    //   jQuery('.alert-danger').html('');

    //     $('#CreateForm').removeClass("btn-warning").addClass('btn-primary');
    //     $('#id').val('');
    //     $('#entrepriseForm').trigger("reset");
    //     $('#modelHeading').html("<i class='glyphicon glyphicon-plus'></i> Nouveau entreprise");
    //     $('#ajaxModel').modal('show');
    // });

    $('#addentreprise').click(function (e) {
      jQuery('.alert-danger').hide();
        jQuery('.alert-danger').html('');
        $('#CreateForm').removeClass("btn-warning").addClass('btn-primary');
        $('#id').val('');
        $('#entrepriseForm').trigger("reset");
        $('#CreateForm').show();
        $('#modelHeading').html("<i class='glyphicon glyphicon-plus'></i> Nouvelle entreprise"); 
        $('.Closer').css('float','left');          
        e.preventDefault();
        $.ajax({
          data:{},
          url: "{{ route('entreprises.create') }}",
          type: "GET",
          // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          dataType: 'json',
          success: function (data) {
              $('.PartianDiv').html(data.html);
              $('#ajaxModel').modal('show');
           
          },
          error: function (data) {
            
          }
      });
    });


    // $("#checkAllUser").change(function () {
      $(document).on('change', '#checkAllUser', function () {
            $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
      
    /*------------------------------------------
    --------------------------------------------
    Click to Edit Button
    --------------------------------------------
    --------------------------------------------*/
   


    $(document).on('click', '.editEntreprise', function (e) {
      jQuery('.alert-danger').hide();
      e.preventDefault();
      var entreprise_id = $(this).data('id');
        jQuery('.alert-danger').html('');
        $('#CreateForm').addClass('btn-warning').removeClass("btn-primary");
        $('#entrepriseForm').trigger("reset");
        $('#CreateForm').show();
        $('#modelHeading').html("<i class='glyphicon glyphicon-edit'></i> Modification d'une entreprise");
        $('.Closer').css('float','left');    
        $.ajax({
          data:{},
          url:"{{ route('entreprises.index') }}" +'/' + entreprise_id +'/edit',
          type: "GET",
          // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          dataType: 'json',
          success: function (data) {
              $('.PartianDiv').html(data.html);
              $('#ajaxModel').modal('show');
          },
          error: function (data) {
            
          }
      });
    });


    $(document).on('click', '.showEntreprise', function (e) {
      jQuery('.alert-danger').hide();
      e.preventDefault();
      var entreprise_id = $(this).data('id');
        jQuery('.alert-danger').html('');
        $('#CreateForm').hide();
        $('#modelHeading').html("<i class='ace-icon fa fa-eye'></i> Details d'une entreprise");
        $('.Closer').css('float','right');      
          // $('#ajaxModel').modal('show');
       
        $.ajax({
          data:{},
          url:"{{ route('entreprises.index') }}" +'/' + entreprise_id,
          type: "GET",
          // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          dataType: 'json',
          success: function (data) {
              $('.PartianDiv').html(data.html);
              $('#ajaxModel').modal('show');
           
          },
          error: function (data) {
            
          }
      });
    });


   
   
      
    /*------------------------------------------
    --------------------------------------------
    Create Product Code
    --------------------------------------------
    --------------------------------------------*/
    $('#CreateForm').click(function (e) {
        e.preventDefault();
        AmagiLoader.show();
        $.ajax({
          data: $('#entrepriseForm').serialize(),
          url: "{{ route('entreprises.store') }}",
          type: "POST",
          // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          dataType: 'json',
          success: function (data) {
              if(data.errors)
                  	{
                  		jQuery('.alert-danger').html('');
                      AmagiLoader.hide();
                  		jQuery.each(data.errors, function(key, value){
                  			jQuery('.alert-danger').show();
                  			jQuery('.alert-danger').append('<li>'+value+'</li>');
                  		});
                  	}
                  	else
                  	{
                      $('#ajaxModel').modal('hide');
                      $('#entrepriseForm').trigger("reset");
              window.location=data.url;
              AmagiLoader.hide();
             
                  	}
           
          },
          error: function (data) {
            
          }
      });
    });

    
    $(document).on('click', '.deleteEntreprise', function () {
          var id = $(this).data('id');
          var Libelle = $(this).attr('role');
          $('#deleteModelHeading').html("<i class='ace-icon fa fa-trash-o bigger-120'></i> Suppression d'un entreprise");
          $('#deleteAjaxModel').modal('show');
          $("#deleteentrepriseid").val(id);
          $("#deleteForm").attr('action','/entreprises/'+id);
          $('.forDelete').html("Voulez-vous vraiment supprimer le entreprise: "+Libelle+"?");
    
    });
    
  });
</script>

@endsection



