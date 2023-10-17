@if($livr!='')
<table>

<tr>
    <td>
        <strong>Date livraison:</strong>
        {{ \Carbon\Carbon::parse($livr->DateReception)->format('d/m/Y')}}
    </td>
</tr>

<tr>
    <td>
        <strong>Date commande:</strong>
        {{ \Carbon\Carbon::parse($livr->commande->DateAchat)->format('d/m/Y')}}
    </td>
</tr>

<tr>
    <td>
        <strong>Montant livraison:</strong> {{number_format($livr->MontantLivre,0,',',' ') }}
        {{ auth()->user()->entreprise->Devise}}
    </td>
</tr>

<tr>
    <td>
        <strong>Fournisseur:</strong> {{$livr->commande->client->Nom}}
    </td>
</tr>

<tr>
    <td>
        <strong>Status livraison:</strong>
        

        @php

if(($livr->commande->MontantLivre/$livr->commande->MontantTTC)*100 < 99.9999 && ($livr->commande->MontantLivre/$livr->commande->MontantTTC)*100 > 99.9)
    {
    $StatusReception=99;
    }
    else
    {
    $StatusReception=round(($livr->commande->MontantLivre/$livr->commande->MontantTTC)*100);
    }
    @endphp
        <div class="progress pos-rel" data-percent="{{$StatusReception}}%">
            <div class="progress-bar" style="width:{{$StatusReception}}%;"></div>
        </div>
    </td>
</tr>
</table>
@endif