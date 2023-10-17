

@if($produit!='')
<tr class='PUnite{{$produit->UniteId}}{{$produit->id}}'>
    <td>{{$produit->Libelle}}</td>
    <td>{{$produit->unite->Nom}}</td>

    <td>
        <select name="FromUniteId[]" id="FromUniteId" class="SelectedUnite2">
            <option value="">Séléctionner une unité</option>
            @foreach($produit->unites as $unite)
            @if($unite->pivot->Qte>0)
            <option value="{{$unite->id}}" {{ (old('FromUniteId')==$unite->id) ?
                                    'selected' : ''}}>
                {{$unite->Nom}}({{number_format($unite->pivot->Qte,0,',',' ') }})
            </option>
            @endif
            @endforeach
        </select>
    </td>

    <td>
    <select name="ToUniteId[]" id="ToUniteId"  class="SelectedUnite1">
            <option value="">Séléctionner une unité</option>
            @foreach($produit->unites as $unite)
            <option value="{{$unite->id}}" {{ (old('ToUniteId')==$unite->id) ?
                                    'selected' : ''}}>
                {{$unite->Nom}}
            </option>
            @endforeach
        </select>
    </td>

    <td hidden><input class='text-right form-control' name='Produit[]' value='{{$produit->id}}' type='number'></td>
    <td hidden><input class='text-right form-control' name='Unite[]' value='{{$produit->UniteId}}' type='number'></td>
    <td><input class='text-right form-control' list='PUnite{{$produit->UniteId}}{{$produit->id}}' name='Qte[]' min='0' value="0" type='number' required /></td>
      <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this' style='width:25px;height:25px;border-radius:100px;' type='button' name='PUnite{{$produit->UniteId}}{{$produit->id}}'><span class='fa fa-trash'></span></button></td>
</tr>
@endif


<script type="text/javascript">
    $(document).ready(function () {
       
        $('.SelectedUnite1').chosen();
        $(".SelectedUnite1_chosen").css("width", "100%");

        
        $('.SelectedUnite2').chosen();
        $(".SelectedUnite2_chosen").css("width", "100%");


    });
</script>