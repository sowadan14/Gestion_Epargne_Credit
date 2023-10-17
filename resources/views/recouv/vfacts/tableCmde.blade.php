@if($livr->produits->count()>0)
                        @foreach ($livr->produits as $livr_produit)

                        @foreach ($livr->commande->produits->where('id',$livr_produit->id) as $cmde_produit)

                        @if($livr_produit->pivot->UniteId==$cmde_produit->pivot->UniteId)
                        <tr class='PUnite{{$livr_produit->pivot->UniteId}}{{$livr_produit->id}}'>
                            <td>{{$cmde_produit->Libelle}}</td>
                            <td>{{$cmde_produit->unites->where('id',$cmde_produit->pivot->UniteId)->first()->Nom}}</td>
                            <td><input class='text-right form-control'
                                    list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Qte[]'
                                    min='0' max="{{$livr_produit->pivot->Qte-$livr_produit->pivot->QteFacture}}" value="{{old('Qte',$livr_produit->pivot->Qte-$livr_produit->pivot->QteFacture)}}" type='number' required />
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
                                    min='0' value="{{old('Produit',$livr_produit->id)}}" type='number' />
                            </td>

                            <td hidden><input class='text-right form-control'
                                    list='PUnite{{$cmde_produit->pivot->UniteId}}{{$cmde_produit->id}}' name='Unite[]'
                                    min='0' value="{{old('Unite',$livr_produit->pivot->UniteId)}}" type='number' />
                            </td>
                        </tr>
                        @break
                        @endif
                        @endforeach
                        @endforeach
                        @endif