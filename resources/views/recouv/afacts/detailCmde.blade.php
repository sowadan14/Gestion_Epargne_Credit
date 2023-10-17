@if($recep!='')
<table>

<tr>
    <td>
        <strong>Date réception:</strong>
        {{ \Carbon\Carbon::parse($recep->DateReception)->format('d/m/Y')}}
    </td>
</tr>

<tr>
    <td>
        <strong>Date commande:</strong>
        {{ \Carbon\Carbon::parse($recep->commande->DateAchat)->format('d/m/Y')}}
    </td>
</tr>

<tr>
    <td>
        <strong>Montant réception:</strong> {{number_format($recep->MontantReçu,0,',',' ') }}
        {{ auth()->user()->entreprise->Devise}}
    </td>
</tr>

<tr>
    <td>
        <strong>Fournisseur:</strong> {{$recep->commande->fournisseur->Nom}}
    </td>
</tr>

<tr>
    <td>
        <strong>Status réception:</strong>
        

        @php

if(($recep->commande->MontantReçu/$recep->commande->MontantTTC)*100 < 99.9999 && ($recep->commande->MontantReçu/$recep->commande->MontantTTC)*100 > 99.9)
    {
    $StatusReception=99;
    }
    else
    {
    $StatusReception=round(($recep->commande->MontantReçu/$recep->commande->MontantTTC)*100);
    }
    @endphp
        <div class="progress pos-rel" data-percent="{{$StatusReception}}%">
            <div class="progress-bar" style="width:{{$StatusReception}}%;"></div>
        </div>
    </td>
</tr>
</table>
@endif