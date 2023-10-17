@if($cmde->produits->count()>0)
@foreach ($cmde->produits as $cmde_produit)
@php

$Quantite= $cmde_produit->pivot->Qte - $cmde_produit->pivot->QteReçu;
@endphp
@if($Quantite!='0')
<tr class='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'>
    <td>{{$cmde_produit->Libelle}}</td>
    <td> {{number_format($cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->pivot->Qte * $cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->pivot->Coef,0,',',' ')}} {{$cmde_produit->unite->Nom}}</td>
    <td hidden><input class='text-right form-control' name='Produit[]' value='{{$cmde_produit->id}}' type='number'></td>
    <td hidden><input class='text-right form-control' name='Unite[]' value='{{$cmde_produit->pivot->UniteId}}' type='number'></td>
    <td>{{number_format($cmde_produit->pivot->Qte,0,',',' ')}} {{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}(s)</td>
    <td>{{number_format($cmde_produit->pivot->QteReçu,0,',',' ')}} {{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}(s)</td>
    <td><input class='text-right form-control' list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Qte[]' min='0' max='{{$Quantite}}' value='{{$Quantite}}' type='number' required /></td>
    <td hidden><input class='text-right form-control' name='OldQteReçu[]' value='{{$cmde_produit->pivot->QteReçu}}' type='number'></td>
    <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this' style='width:25px;height:25px;border-radius:100px;' type='button' name='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'><span class='fa fa-trash'></span></button></td>
</tr>
@endif
@endforeach
@endif