@if($recep->produits->count()>0)
                        @foreach ($recep->produits as $recep_produit)

                        @foreach ($recep->commande->produits->where('id',$recep_produit->id) as $cmde_produit)

                        @if($recep_produit->pivot->UniteId==$cmde_produit->pivot->UniteId)
                        <tr class='PUnite{{$recep_produit->pivot->UniteId}}{{$recep_produit->id}}'>
                            <td>{{$cmde_produit->Libelle}}</td>
                            <td>{{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}</td>
                            <td><input class='text-right form-control'
                                    list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Qte[]'
                                    min='0' max="{{$recep_produit->pivot->Qte-$recep_produit->pivot->QteFacture}}" value="{{old('Qte',$recep_produit->pivot->Qte-$recep_produit->pivot->QteFacture)}}" type='number' required />
                            </td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Prix,0,',',' ') }}</td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Remise,0,',',' ') }}</td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Tva,0,',',' ') }}</td>
                            <td class='text-right'>{{number_format($cmde_produit->pivot->Montant,0,',',' ') }}</td>
                            <td style='text-align:center;'><button class='btn btn-danger btn-sm remove_this'
                                    style='width:25px;height:25px;border-radius:100px;' type='button'
                                    name='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}'><span
                                        class='fa fa-trash'></span></button>
                                    </td>
                                    <td hidden><input class='text-right form-control'
                                    list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Produit[]'
                                    min='0' value="{{old('Produit',$recep_produit->id)}}" type='number' />
                            </td>

                            <td hidden><input class='text-right form-control'
                                    list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Unite[]'
                                    min='0' value="{{old('Unite',$recep_produit->pivot->UniteId)}}" type='number' />
                            </td>
                        </tr>
                        @break
                        @endif
                        @endforeach
                        @endforeach
                        @endif