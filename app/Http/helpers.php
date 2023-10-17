<?php

use App\Models\Produit;
use App\Models\DetailsAvoirFr;
use App\Models\DetailsAvoirClt;
use App\Models\Achat;
use App\Models\AvoirFr;
use App\Models\AvoirClt;
use App\Models\Vente;
use App\Models\Compte;
use App\Models\DetailsCompte;
use App\Models\FactureAchat;
use App\Models\FactureVente;
use App\Models\LivraisonVente;
use App\Models\ModePaiement;
use App\Models\PaiementAchat;
use App\Models\PaiementVente;
use App\Models\ReceptionAchat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\Exists;

if (!function_exists('generateCmdeAchat')) {
  function generateCmdeAchat()
  {
    $totalCmdes = count(DB::table('achats')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get());
    // dd($totalCmdes);
    $totalCmdes = $totalCmdes + 1;
    $ref = "CMD-FR" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('achats')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('Reference', $ref)->get()) > 1) {
      $totalCmdes = $totalCmdes + 1;
      $ref = "CMD-FR" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}


if (!function_exists('generateCmdeVente')) {
  function generateCmdeVente()
  {
    $totalCmdes = count(DB::table('ventes')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get());
    // dd($totalCmdes);
    $totalCmdes = $totalCmdes + 1;
    $ref = "CMD-CL" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('ventes')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('Reference', $ref)->get()) > 1) {
      $totalCmdes = $totalCmdes + 1;
      $ref = "CMD-CL" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}


function addDetailsProduit($produitId, $uniteId, $qte)
{
  if ($produitId != '' && $uniteId != '' && $qte != '' && $qte > 0) {
    $produit = Produit::find($produitId);

    // $Coef = $produit->unites->where('id', $uniteId)->first()->pivot->Coef;
    $OldQte = $produit->unites->where('id', $uniteId)->first()->pivot->Qte;

    if ($OldQte != '' || $OldQte != '0') {
      DB::table('detailsachats')
        ->where('ProduitId', $produitId)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => $OldQte + $qte]);
      // $produit->Qte = $produit->Qte + ($Coef * $qte);
      // $produit->save();
    }
  }
}


function AddReceptionDetailsProduit($produitId, $uniteId, $qte, $cmdeId)
{
  if ($produitId != '' && $uniteId != '' && $qte != '' && $qte > 0) {
    $produit = Produit::find($produitId);

    $Coef = $produit->unites->where('id', $uniteId)->first()->pivot->Coef;

    if (($Coef != '' || $Coef != '0')) {
      DB::table('detailsachats')
        ->where('AchatId', $cmdeId)
        ->where('ProduitId', $produitId)
        ->where('UniteId', $uniteId)
        ->update(['QteReçu' => DB::raw('QteReçu+' . $qte)]);


      DB::table('uniteproduits')
        ->where('ProduitId', $produitId)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => DB::raw('Qte+' . $qte)]);

      $produit->Qte = $produit->Qte + ($Coef * $qte);
      $produit->save();
    }
  }
}



function AddLivraisonDetailsProduit($produitId, $uniteId, $qte, $cmdeId)
{
  dd($produitId,$uniteId,$qte,$cmdeId);
  if ($produitId != '' && $uniteId != '' && $qte != '' && $qte > 0) {
    $produit = Produit::find($produitId);

    $Coef = $produit->unites->where('id', $uniteId)->first()->pivot->Coef;

    if (($Coef != '' || $Coef != '0')) {
      DB::table('detailsventes')
        ->where('VenteId', $cmdeId)
        ->where('ProduitId', $produitId)
        ->where('UniteId', $uniteId)
        ->update(['QteLivre' => DB::raw('QteLivre+' . $qte)]);


      DB::table('uniteproduits')
        ->where('ProduitId', $produitId)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => DB::raw('Qte-' . $qte)]);

      $produit->Qte = $produit->Qte - ($Coef * $qte);
      $produit->save();
    }
  }
}


function DetailsProduit($produitId, $uniteId, $qte)
{
  if ($produitId != '' && $uniteId != '' && $qte != '' && $qte > 0) {
    $produit = Produit::find($produitId);

    $Coef = $produit->unites->where('id', $uniteId)->first()->pivot->Coef;

    if (($Coef != '' || $Coef != '0')) {
      DB::table('uniteproduits')
        ->where('ProduitId', $produitId)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => DB::raw('Qte+' . $qte)]);

      $produit->Qte = $produit->Qte + ($Coef * $qte);
      $produit->save();
    }
  }
}


function DetailsVenteProduit($produitId, $uniteId, $qte)
{
  if ($produitId != '' && $uniteId != '' && $qte != '' && $qte > 0) {
    $produit = Produit::find($produitId);

    $Coef = $produit->unites->where('id', $uniteId)->first()->pivot->Coef;

    if (($Coef != '' || $Coef != '0')) {
      DB::table('uniteproduits')
        ->where('ProduitId', $produitId)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => DB::raw('Qte-' . $qte)]);

      $produit->Qte = $produit->Qte + ($Coef * $qte);
      $produit->save();
    }
  }
}


function AddFacturationDetailsProduit($produitId, $uniteId, $qte, $recepId)
{
  // dd($produitId,$uniteId,$qte,$recepId);
  if ($produitId != '' && $uniteId != '' && $qte != '' && $qte > 0  && $recepId != '') {
    DB::table('detailsreceptionachats')
      ->where('ReceptionId', $recepId)
      ->where('ProduitId', $produitId)
      ->where('UniteId', $uniteId)
      ->update(['QteFacture' => DB::raw('QteFacture+' . $qte)]);
  }
}


function AddFacturationVenteDetailsProduit($produitId, $uniteId, $qte, $livrId)
{
  // dd($produitId,$uniteId,$qte,$recepId);
  if ($produitId != '' && $uniteId != '' && $qte != '' && $qte > 0  && $livrId != '') {
    DB::table('detailslivraisonventes')
      ->where('LivraisonId', $livrId)
      ->where('ProduitId', $produitId)
      ->where('UniteId', $uniteId)
      ->update(['QteFacture' => DB::raw('QteFacture+' . $qte)]);
  }
}


function removeDetailsProduit($cmdeId)
{
  $cmde = Achat::find($cmdeId);
  foreach ($cmde->produits as $produit) {
    $uniteId = $produit->pivot->UniteId;
    $OldQte = $produit->unites->where('id', $uniteId)->first()->pivot->Qte;
    $Qte = $produit->pivot->Qte;

    if (($OldQte != '' || $OldQte != '0') && ($Qte != '' || $Qte != '0')) {
      DB::table('uniteproduits')
        ->where('ProduitId', $produit->id)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => $OldQte - $Qte]);
    }
  }
}



function DeleteAchat($cmdeId)
{
  $cmde = Achat::find($cmdeId);
  foreach ($cmde->produits as $produit) {
    $uniteId = $produit->pivot->UniteId;
    $Qte = $produit->pivot->Qte;



    if (($Qte != '' || $Qte != '0')) {
      DB::table('uniteproduits')
        ->where('ProduitId', $produit->id)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => DB::raw('Qte-' . $Qte)]);
    }
  }

  // dd($cmde);

  $cmde->produits()->detach();
  $cmde->Supprimer = true;
  $cmde->Delete_user = Auth::user()->id;
  $cmde->save();

  foreach ($cmde->receptionachats as $livraison) {
    $urecep = ReceptionAchat::find($livraison->id);

    foreach ($urecep->factureachats as $afact) {

      $uafact = FactureAchat::find($afact->id);
      foreach ($uafact->paiements as $paiement) {
        $upaiement = PaiementAchat::find($paiement->id);
        $upaiement->Supprimer = true;
        $upaiement->Delete_user = Auth::user()->id;
        $upaiement->save();




        $compte = Compte::find($paiement->CompteId);
        if ($compte != null) {
          $compte->Solde = $compte->Solde + $paiement->Montant;
          $compte->save();

          $modepaiement = ModePaiement::find($upaiement->ModePaiementId);
          if ($modepaiement != null) {
            $libelle = "Annu. paiement " . $modepaiement->Nom . " facture " . $paiement->Reference;
            AddDetailsCompte($compte->id, $libelle, 0,$paiement->Montant - $paiement->MontantAvoir,);
          }

          if ($upaiement->PaidWithAvoir) {
            $libelle = "Annu. paiement avoir facture " . $paiement->Reference;
            AddDetailsCompte($compte->id, $libelle, 0, $paiement->MontantAvoir);

            $avoirfr = AvoirFr::where('FournisseurId', $cmde->FournisseurId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();

            if ($avoirfr != null) {
              $avoirfr = AvoirFr::find($avoirfr->id);
              if ($paiement->MontantAvoir > 0) {
                //ajout nouveau montant avoir
                $avoirfr->Montant = $avoirfr->Montant + $paiement->MontantAvoir;
                $avoirfr->Edit_user = Auth::user()->id;
                $avoirfr->Modif_util = Carbon::now();
                $avoirfr->save();

                $libelle = "Avoir sur facture " . $paiement->Reference;
                AddDetailsAvoirFr($avoirfr->id, $libelle, 1, $paiement->MontantAvoir);

                if ($compte != null) {
                  $compte->Solde = $compte->Solde + $paiement->MontantAvoir;
                  $compte->save();

                  AddDetailsCompte($compte->id, $libelle, 0, $paiement->MontantAvoir);
                }
              }
            }
          }
        }
      }
      $uafact->produits()->detach();

      $uafact->Supprimer = true;
      $uafact->Delete_user = Auth::user()->id;
      $uafact->save();
    }
    $urecep->produits()->detach();

    $urecep->Supprimer = true;
    $urecep->Delete_user = Auth::user()->id;
    $urecep->save();
  }
}


function DeleteVente($cmdeId)
{
  $cmde = Vente::find($cmdeId);
  foreach ($cmde->produits as $produit) {
    $uniteId = $produit->pivot->UniteId;
    $Qte = $produit->pivot->Qte;



    if (($Qte != '' || $Qte != '0')) {
      DB::table('uniteproduits')
        ->where('ProduitId', $produit->id)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => DB::raw('Qte+' . $Qte)]);
    }
  }

  // dd($cmde);

  $cmde->produits()->detach();
  $cmde->Supprimer = true;
  $cmde->Delete_user = Auth::user()->id;
  $cmde->save();

  foreach ($cmde->livraisonventes as $livraison) {
    $ulivr = LivraisonVente::find($livraison->id);

    foreach ($ulivr->factureventes as $vfact) {

      $uvfact = FactureVente::find($vfact->id);
      foreach ($uvfact->paiements as $paiement) {
        $upaiement = PaiementVente::find($paiement->id);
        $upaiement->Supprimer = true;
        $upaiement->Delete_user = Auth::user()->id;
        $upaiement->save();

        $compte = Compte::find($paiement->CompteId);
        if ($compte != null) {
          $compte->Solde = $compte->Solde - $paiement->Montant;
          $compte->save();

          $modepaiement = ModePaiement::find($upaiement->ModePaiementId);
          if ($modepaiement != null) {
            $libelle = "Annu. paiement " . $modepaiement->Nom . " facture " . $paiement->Reference;
            AddDetailsCompte($compte->id, $libelle, $paiement->Montant - $paiement->MontantAvoir, 0);
          }

          if ($upaiement->PaidWithAvoir) {
            $libelle = "Annu. paiement avoir facture " . $paiement->Reference;
            AddDetailsCompte($compte->id, $libelle, $paiement->MontantAvoir, 0);

            $avoirclt = AvoirClt::where('ClientId', $cmde->ClientId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();

            if ($avoirclt != null) {
              $avoirclt = AvoirClt::find($avoirclt->id);
              if ($paiement->MontantAvoir > 0) {
                //ajout nouveau montant avoir
                $avoirclt->Montant = $avoirclt->Montant + $paiement->MontantAvoir;
                $avoirclt->Edit_user = Auth::user()->id;
                $avoirclt->Modif_util = Carbon::now();
                $avoirclt->save();

                $libelle = "Avoir sur facture " . $paiement->Reference;
                AddDetailsAvoirClt($avoirclt->id, $libelle, 1, $paiement->MontantAvoir);

                if ($compte != null) {
                  $compte->Solde = $compte->Solde + $paiement->MontantAvoir;
                  $compte->save();

                  AddDetailsCompte($compte->id, $libelle, 0, $paiement->MontantAvoir);
                }
              }
            }
          }
        }
      }
      $uvfact->produits()->detach();

      $uvfact->Supprimer = true;
      $uvfact->Delete_user = Auth::user()->id;
      $uvfact->save();
    }
    $ulivr->produits()->detach();

    $ulivr->Supprimer = true;
    $ulivr->Delete_user = Auth::user()->id;
    $ulivr->save();
  }
}


function AddDetailsCompte($compteId, $libelle, $debit, $credit)
{
  $detailcompte = new DetailsCompte();
  $detailcompte->DateOperation = Carbon::now();
  $detailcompte->Libelle = $libelle;
  // $detailcompte->Entree = $entree;
  $detailcompte->CompteId = $compteId;
  $detailcompte->Credit = $credit;
  $detailcompte->Debit = $debit;
  $detailcompte->EntrepriseId = Auth::user()->EntrepriseId;
  $detailcompte->Create_user = Auth::user()->id;
  $detailcompte->save();
}

function AjoutDetailsCompte($compteId, $libelle, $debit, $credit,$dateOperation)
{
  $detailcompte = new DetailsCompte();
  $detailcompte->DateOperation = $dateOperation;
  $detailcompte->Libelle = $libelle;
  // $detailcompte->Entree = $entree;
  $detailcompte->CompteId = $compteId;
  $detailcompte->Credit = $credit;
  $detailcompte->Debit = $debit;
  $detailcompte->EntrepriseId = Auth::user()->EntrepriseId;
  $detailcompte->Create_user = Auth::user()->id;
  $detailcompte->save();
}

function AddDetailsAvoirFr($AvoirFrId, $libelle, $entree, $montant)
{
  $detailavoirfr = new DetailsAvoirFr();
  $detailavoirfr->DateAvoir = Carbon::now();
  $detailavoirfr->Libelle = $libelle;
  $detailavoirfr->AvoirFrId = $AvoirFrId;
  $detailavoirfr->Entree = $entree;
  $detailavoirfr->Montant = $montant;
  $detailavoirfr->EntrepriseId = Auth::user()->EntrepriseId;
  $detailavoirfr->save();
}

function AddDetailsAvoirClt($AvoirCltId, $libelle, $entree, $montant)
{
  $detailavoirclt = new DetailsAvoirClt();
  $detailavoirclt->DateAvoir = Carbon::now();
  $detailavoirclt->Libelle = $libelle;
  $detailavoirclt->AvoirCltId = $AvoirCltId;
  $detailavoirclt->Entree = $entree;
  $detailavoirclt->Montant = $montant;
  $detailavoirclt->EntrepriseId = Auth::user()->EntrepriseId;
  $detailavoirclt->save();
}

function removeReceptionDetailsProduit($recepId)
{
  $recep = ReceptionAchat::find($recepId);
  foreach ($recep->produits as $produit) {
    $uniteId = $produit->pivot->UniteId;
    $Coef = $produit->unites->where('id', $uniteId)->first()->pivot->Coef;
    $Qte = $produit->pivot->Qte;

    if (($Coef != '' || $Coef != '0') && ($Qte != '' || $Qte != '0')) {
      //Mettre à jour la qté des achats
      DB::table('detailsachats')
        ->where('AchatId', $recep->AchatId)
        ->where('ProduitId', $produit->id)
        ->where('UniteId', $uniteId)
        ->update(['QteReçu' => DB::raw('QteReçu-' . $Qte)]);

      //Mettre à jour la qté des produits
      DB::table('uniteproduits')
        ->where('ProduitId', $produit->id)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => DB::raw('Qte-' . $Qte)]);

      $produit->Qte = $produit->Qte - ($Coef * $Qte);
      $produit->save();

      $cmde = Achat::find($recep->AchatId);
      if ($cmde != null) {
        $cmde->MontantReçu = $cmde->MontantReçu - $recep->MontantReçu;
        $cmde->Status = 0;
        $cmde->save();
      }

      $recep->MontantReçu = 0;
      $recep->save();
    }
  }
}


function removeLivraisonDetailsProduit($livrId)
{
  $livr = LivraisonVente::find($livrId);
  foreach ($livr->produits as $produit) {
    $uniteId = $produit->pivot->UniteId;
    $Coef = $produit->unites->where('id', $uniteId)->first()->pivot->Coef;
    $Qte = $produit->pivot->Qte;

    if (($Coef != '' || $Coef != '0') && ($Qte != '' || $Qte != '0')) {
      //Mettre à jour la qté des achats
      DB::table('detailsventes')
        ->where('VenteId', $livr->VenteId)
        ->where('ProduitId', $produit->id)
        ->where('UniteId', $uniteId)
        ->update(['QteLivre' => DB::raw('QteLivre-' . $Qte)]);

      //Mettre à jour la qté des produits
      DB::table('uniteproduits')
        ->where('ProduitId', $produit->id)
        ->where('UniteId', $uniteId)
        ->update(['Qte' => DB::raw('Qte+' . $Qte)]);

      $produit->Qte = $produit->Qte + ($Coef * $Qte);
      $produit->save();

      $cmde = Vente::find($livr->VenteId);
      if ($cmde != null) {
        $cmde->MontantLivre = $cmde->MontantLivre - $livr->MontantLivre;
        $cmde->Status = 0;
        $cmde->save();
      }

      $livr->MontantLivre = 0;
      $livr->save();
    }
  }
}

function removeFacturationDetailsProduit($afactId)
{
  $afact = FactureAchat::find($afactId);
  foreach ($afact->produits as $produit) {
    $uniteId = $produit->pivot->UniteId;
    $Qte = $produit->pivot->Qte;

    if ($Qte != '' || $Qte != '0') {
      //Mettre à jour la qté des achats  
      // dd($Qte); 
      DB::table('detailsreceptionachats')
        ->where('ReceptionId', $afact->ReceptionId)
        ->where('ProduitId', $produit->id)
        ->where('UniteId', $uniteId)
        ->update(['QteFacture' => DB::raw('QteFacture-' . $Qte)]);
    }
  }
}


function removeFacturationVenteDetailsProduit($vfactId)
{
  $vfact = FactureVente::find($vfactId);
  foreach ($vfact->produits as $produit) {
    $uniteId = $produit->pivot->UniteId;
    $Qte = $produit->pivot->Qte;

    if ($Qte != '' || $Qte != '0') {
      //Mettre à jour la qté des achats  
      // dd($Qte); 
      DB::table('detailslivraisonventes')
        ->where('LivraisonId', $vfact->LivraisonId)
        ->where('ProduitId', $produit->id)
        ->where('UniteId', $uniteId)
        ->update(['QteFacture' => DB::raw('QteFacture-' . $Qte)]);
    }
  }
}




function removePaiement($paiementId)
{
  $paiement =  PaiementAchat::find($paiementId);

  if ($paiement != null) {
    $afact =  FactureAchat::find($paiement->FactureId);
    $recep = ReceptionAchat::find($afact->ReceptionId);
    $cmde = Achat::find($recep->AchatId);
    $compte = Compte::find($paiement->CompteId);
    if ($afact != null && $recep != null && $cmde != null && $compte != null) {

      $compte->Solde = $compte->Solde - $paiement->Montant;
      $compte->save();

      if ($paiement->PaidWithAvoir && $paiement->MontantAvoir > 0) {
        $libelle = "Annu. paiement avoir facture " . $paiement->Reference;
        AddDetailsCompte($compte->id, $libelle, $paiement->MontantAvoir, 0);

        $avoirfr = AvoirFr::where('FournisseurId', $cmde->FournisseurId)->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->first();

        if ($avoirfr != null) {
          $avoirfr = AvoirFr::find($avoirfr->id);
          if ($paiement->MontantAvoir > 0) {
            //ajout nouveau montant avoir
            $avoirfr->Montant = $avoirfr->Montant + $paiement->MontantAvoir;
            $avoirfr->Edit_user = Auth::user()->id;
            $avoirfr->Modif_util = Carbon::now();
            $avoirfr->save();

            $libelle = "Avoir sur facture " . $paiement->Reference;
            AddDetailsAvoirFr($avoirfr->id, $libelle, 1, $paiement->MontantAvoir);

            if ($compte != null) {
              $compte->Solde = $compte->Solde + $paiement->MontantAvoir;
              $compte->save();

              AddDetailsCompte($compte->id, $libelle, 0, $paiement->MontantAvoir);
            }
          }
        }
      }


      if ($paiement->Montant > $paiement->MontantAvoir) {
        $modepaiement = ModePaiement::find($paiement->ModePaiementId);
        if ($modepaiement != null) {
          $libelle = "Annu. paiement " . $modepaiement->Nom . " facture " . $paiement->Reference;
          AddDetailsCompte($compte->id, $libelle, $paiement->Montant - $paiement->MontantAvoir, 0);
        }
      }
    }
  }
}




// function UpdateReceptionDetailsProduit($produitId, $uniteId, $OldQteReçu, $recepId)
// {
//   $recep = ReceptionAchat::find($recepId);
//   if ($produitId != '' && $uniteId != '' && $uniteId != '' && $uniteId > 0) {
//     $produit = Produit::find($produitId);
//     $Coef = $produit->unites->where('id', $uniteId)->first()->pivot->Coef;
//     $OldQte = $produit->unites->where('id', $uniteId)->first()->pivot->Qte;

//     if (($Coef != '' || $Coef != '0') && ($OldQte != '' || $OldQte != '0') && ($OldQteReçu != '' || $OldQteReçu != '0')) {
//       DB::table('detailsachats')
//         ->where('ProduitId', $produitId)
//         ->where('UniteId', $uniteId)
//         ->update(['QteReçu' => $OldQteReçu + $qte]);


//       DB::table('uniteproduits')
//         ->where('ProduitId', $produitId)
//         ->where('UniteId', $uniteId)
//         ->update(['Qte' => $OldQte + $qte]);

//       $produit->Qte = $produit->Qte + ($Coef * $qte);
//       $produit->save();
//     }
//   }
// }


function updateDetailsProduit($produitId, $uniteId, $qte)
{
  if ($produitId != '' && $uniteId != '' && $qte != '' && $qte > 0) {
    $produit = Produit::find($produitId);

    $Coef = $produit->unites->where('UniteId', $uniteId)->first()->Coef;
    $OldQte = $produit->unites->where('UniteId', $uniteId)->first()->Qte;

    if ($Coef != '' && $Coef > 0 && $OldQte != '' && $OldQte > 0) {
      $produit->unites->where('UniteId', $uniteId)->updateExistingPivot('Qte', $OldQte + $qte);
      $produit->Qte = $produit->Qte + ($Coef * $qte);
      $produit->save();
    }
  }
}


function updateStockProduit($produitId, $uniteId, $qte)
{
  if ($produitId != '' && $uniteId != '' && $qte != '' && $qte > 0) {
    $produit = Produit::find($produitId);

    $Coef = $produit->unites->where('UniteId', $uniteId)->first()->Coef;
    $OldQte = $produit->unites->where('UniteId', $uniteId)->first()->Qte;

    if ($Coef != '' && $Coef > 0 && $OldQte != '' && $OldQte > 0) {
      $produit->unites->where('UniteId', $uniteId)->updateExistingPivot('Qte', $OldQte + $qte);
      $produit->Qte = $produit->Qte + ($Coef * $qte);
      $produit->save();
    }
  }
}

if (!function_exists('generateRecepAchat')) {
  function generateRecepAchat()
  {
    $totalCmdes = count(DB::table('receptionachats')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get());
    // dd($totalCmdes);
    $totalCmdes = $totalCmdes + 1;
    $ref = "REC-FR" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('receptionachats')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('Reference', $ref)->get()) > 1) {
      $totalCmdes = $totalCmdes + 1;
      $ref = "REC-FR" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}


if (!function_exists('generateFactAchat')) {
  function generateFactAchat()
  {
    $totalCmdes = count(DB::table('factureachats')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get());
    // dd($totalCmdes);
    $totalCmdes = $totalCmdes + 1;
    $ref = "FAC-FR" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('factureachats')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('Reference', $ref)->get()) > 1) {
      $totalCmdes = $totalCmdes + 1;
      $ref = "FAC-FR" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}


if (!function_exists('generatePaiementAchat')) {
  function generatePaiementAchat()
  {
    $totalCmdes = count(DB::table('paiementachats')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get());
    // dd($totalCmdes);
    $totalCmdes = $totalCmdes + 1;
    $ref = "PAI-FR" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('paiementachats')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('Reference', $ref)->get()) > 1) {
      $totalCmdes = $totalCmdes + 1;
      $ref = "PAI-FR" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}


if (!function_exists('generateRefAchat')) {
  function generateRefAchat($CmdeId)
  {
    $ref = "A" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "" . str_pad($CmdeId, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('achats')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('RefAchat', $ref)->get()) > 1) {
      $CmdeId = $CmdeId + 1;
      $ref = "A" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "" . str_pad($CmdeId, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}



if (!function_exists('generateLivrVente')) {
  function generateLivrVente()
  {
    $totalCmdes = count(DB::table('livraisonventes')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get());
    // dd($totalCmdes);
    $totalCmdes = $totalCmdes + 1;
    $ref = "LIV-CL" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('livraisonventes')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('Reference', $ref)->get()) > 1) {
      $totalCmdes = $totalCmdes + 1;
      $ref = "LIV-CL" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}


if (!function_exists('generateFactVente')) {
  function generateFactVente()
  {
    $totalCmdes = count(DB::table('factureventes')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get());
    // dd($totalCmdes);
    $totalCmdes = $totalCmdes + 1;
    $ref = "FAC-CL" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('factureventes')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('Reference', $ref)->get()) > 1) {
      $totalCmdes = $totalCmdes + 1;
      $ref = "FAC-CL" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}


if (!function_exists('generatePaiementVente')) {
  function generatePaiementVente()
  {
    $totalCmdes = count(DB::table('paiementventes')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->get());
    // dd($totalCmdes);
    $totalCmdes = $totalCmdes + 1;
    $ref = "PAI-CL" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('paiementventes')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('Reference', $ref)->get()) > 1) {
      $totalCmdes = $totalCmdes + 1;
      $ref = "PAI-CL" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "-" . str_pad($totalCmdes, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}


if (!function_exists('generateRefVente')) {
  function generateRefVente($CmdeId)
  {
    $ref = "V" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "" . str_pad($CmdeId, 4, '0', STR_PAD_LEFT);


    while (count(DB::table('ventes')->where('Supprimer', false)->where('EntrepriseId', Auth::user()->EntrepriseId)->where('RefVente', $ref)->get()) > 1) {
      $CmdeId = $CmdeId + 1;
      $ref = "V" . str_pad(Carbon::now()->day, 2, '0', STR_PAD_LEFT) . "" . str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT) . "" . Carbon::now()->format("y") . "" . str_pad($CmdeId, 4, '0', STR_PAD_LEFT);
    }
    return  $ref;
  }
} else {
  return  "";
}
