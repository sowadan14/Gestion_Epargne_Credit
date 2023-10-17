<table>
                    <tr>
                        <td>
                            <strong>Date commande:</strong> {{ \Carbon\Carbon::parse($cmde->DateAchat)->format('d/m/Y')}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Montant TTC:</strong> {{number_format($cmde->MontantTTC,0,',',' ') }} {{ auth()->user()->entreprise->Devise}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Client:</strong> {{$cmde->client->Nom}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Status livraison:</strong> 
                            @php

if(($cmde->MontantLivre/$cmde->MontantTTC)*100 < 99.9999 && ($cmde->MontantLivre/$cmde->MontantTTC)*100 > 99.9)
    {
    $StatusReception=99;
    }
    else
    {
    $StatusReception=round(($cmde->MontantLivre/$cmde->MontantTTC)*100);
    }
    @endphp


                          
                            <div class="progress pos-rel" data-percent="{{$StatusReception}}%">
                                <div class="progress-bar" style="width:{{$StatusReception}}%;"></div>
                            </div>
                        </td>
                    </tr>
                </table>