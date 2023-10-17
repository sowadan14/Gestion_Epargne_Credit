<div id="sidebar" class="sidebar responsive ace-save-state" style="background-color:{{auth()->user()->entreprise->ColorSidebar}}; color:black; font-weight:bold;">

  <div class="sidebar-shortcuts" id="sidebar-shortcuts" style="background-color:{{auth()->user()->entreprise->ColorSidebar}}; color:black; font-weight:bold;">
    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">

    <a href="{{url('/config/users')}}" title="Utilisateurs" class="btn btn-success"><i class="icon-user4 bigger-200"></i></a>

    <a href="{{url('/config/params')}}" title="Paramètres" class="btn btn-info"><i class="icon-android-settings  bigger-200"></i></a>

    <a href="{{url('/achats')}}" title="Achats" class="btn btn-warning"><i class="menu-icon icon-cart31  bigger-200"></i></a>

    <a href="{{url('/ventes')}}" title="Ventes" class="btn btn-light"><i class="menu-icon icon-cart32  bigger-200"></i></a>
    </div>

    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini" >
      <span class="btn btn-success"></span>

      <span class="btn btn-info"></span>

      <span class="btn btn-warning"></span>

      <span class="btn btn-light"></span>
    </div>
  </div><!-- /.sidebar-shortcuts -->
  <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse" style="background-color:{{auth()->user()->entreprise->ColorSidebar}}; color:black; font-weight:bold;">
    <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
      data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"
      style="background-color: #1c6bef;color: #ffffff;"></i>
  </div>

  <ul class="nav nav-list">
    <li class="">
      <a href="home">
        <i class="menu-icon icon-ios-speedometer"></i>
        <span class="menu-text"> Dashboard </span>
      </a>

      <b class="arrow"></b>
    </li>

    <li rel="client" class="client">
      <a href="{{url('clients')}}">
        <i class="menu-icon icon-group bigger-130"></i>
        <span class="menu-text"> Clients </span>
      </a>

      <b class="arrow"></b>
    </li>

    <li rel="fournisseur" class="fournisseur">
      <a href="{{url('/frs')}}">
        <i class="menu-icon icon-group bigger-130"></i>
        <span class="menu-text"> Fournisseurs </span>
      </a>

      <b class="arrow"></b>
    </li>

    <li class="achat">
      <a href="#" class="dropdown-toggle">
        <i class="menu-icon menu-icon icon-cart31 bigger-130"></i>
        <span class="menu-text"> Achats </span>
        <b class="arrow fa fa-angle-down"></b>
      </a>

      <b class="arrow"></b>

      <ul class="submenu">

      <li rel="listachat" class="listachat" role="achat">
          <a href="{{url('/achat/alists')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Listes
          </a>
          <b class="arrow"></b>
        </li>
       
        <li rel="achatcmde" class="achatcmde" role="achat">
          <a href="{{url('/achat/acmdes')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Commandes
          </a>
          <b class="arrow"></b>
        </li>

        <li  rel="recep" class="recep" role="achat">
          <a href="{{url('/achat/receps')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Réceptions
          </a>
          <b class="arrow"></b>
        </li>

        <!-- <li  rel="afact" class="afact" role="achat">
          <a href="{{url('/achat/afacts')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Factures
          </a>
          <b class="arrow"></b>
        </li> -->


      </ul>
    </li>

    <li class="vente">
      <a href="#" class="dropdown-toggle">
        <i class="menu-icon menu-icon icon-cart32 bigger-130"></i>
        <span class="menu-text"> Ventes </span>
        <b class="arrow fa fa-angle-down"></b>
      </a>

      <b class="arrow"></b>

      <ul class="submenu">

      <li rel="listvente" class="listvente" role="vente">
          <a href="{{url('/vente/vlists')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Listes
          </a>
          <b class="arrow"></b>
        </li>
       
        <li rel="ventecmde" class="ventecmde" role="vente">
          <a href="{{url('/vente/vcmdes')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Commandes
          </a>
          <b class="arrow"></b>
        </li>

        <li  rel="livr" class="livr" role="vente">
          <a href="{{url('/vente/livrs')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Livraisons
          </a>
          <b class="arrow"></b>
        </li>
      </ul>
    </li>


    <li class="recouv">
      <a href="#" class="dropdown-toggle">
        <i class="menu-icon icon-money bigger-130"></i>
        <span class="menu-text"> Recouvrements </span>
        <b class="arrow fa fa-angle-down"></b>
      </a>

      <b class="arrow"></b>

      <ul class="submenu">

      <li  rel="etatfr" class="etatfr" role="recouv">
          <a href="{{url('/recouv/etatfrs')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Etat fournisseur
          </a>
          <b class="arrow"></b>
        </li>

      <li  rel="avoirfr" class="avoirfr" role="recouv">
          <a href="{{url('/recouv/avoirfrs')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Avoir fournisseur
          </a>
          <b class="arrow"></b>
        </li>

        <li  rel="afact" class="afact" role="recouv">
          <a href="{{url('/recouv/afacts')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Facture fournisseur
          </a>
          <b class="arrow"></b>
        </li>

        <li rel="apaiement" class="apaiement" role="recouv">
          <a href="{{url('/recouv/apaiements')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Paiement fournisseur
          </a>
          <b class="arrow"></b>
        </li>

        
      <li  rel="etatclt" class="etatclt" role="recouv">
          <a href="{{url('/recouv/etatclts')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Etat client
          </a>
          <b class="arrow"></b>
        </li>

        <li  rel="avoirclt" class="avoirclt" role="recouv">
          <a href="{{url('/recouv/avoirclts')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Avoir client
          </a>
          <b class="arrow"></b>
        </li>

        <li  rel="vfact" class="vfact" role="recouv">
          <a href="{{url('/recouv/vfacts')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Facture client
          </a>
          <b class="arrow"></b>
        </li>


        <li rel="vpaiement" class="vpaiement" role="recouv">
          <a href="{{url('/recouv/vpaiements')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Paiement client
          </a>
          <b class="arrow"></b>
        </li>

      </ul>
    </li>


    
    <li  class="stock">
      <a href="#" class="dropdown-toggle">
        <i class="menu-icon icon-product-hunt   bigger-130"></i>
        <span class="menu-text"> Stock </span>
        <b class="arrow fa fa-angle-down"></b>
      </a>

      <b class="arrow"></b>

      <ul class="submenu">
        <li  rel="stockprodt" class="stockprodt" role="stock">
          <a href="{{url('/stock/stockprodts')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Stock produit
          </a>
          <b class="arrow"></b>
        </li>

        <li  rel="convertstock" class="convertstock" role="stock">
        <a href="{{url('/stock/convertstocks')}}">
        <i class="menu-icon fa fa-circle-o"></i>
            Conversion stock
          </a>

          <b class="arrow"></b>
        </li>


      </ul>
    </li>


    <li class="regul">
      <a href="#" class="dropdown-toggle">
        <i class="menu-icon icon-android-compass  bigger-130"></i>
        <span class="menu-text"> Régularisation </span>
        <b class="arrow fa fa-angle-down"></b>
      </a>

      <b class="arrow"></b>

      <ul class="submenu">
        <li   rel="regulstock" class="regulstock" role="regul">
          <a href="{{url('/regul/regulstocks')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Stock
          </a>
          <b class="arrow"></b>
        </li>

        <li  rel="regulcompte" class="regulcompte" role="regul">
        <a href="{{url('/regul/regulcomptes')}}">
        <i class="menu-icon fa fa-circle-o"></i>
            Compte
          </a>

          <b class="arrow"></b>
        </li>


      </ul>
    </li>

    <li class="depense">
      <a href="#" class="dropdown-toggle">
        <i class="menu-icon icon-money bigger-130"></i>
        <span class="menu-text"> Dépenses </span>
        <b class="arrow fa fa-angle-down"></b>
      </a>

      <b class="arrow"></b>

      <ul class="submenu">
        <li   rel="typedepense" class="typedepense" role="depense">
          <a href="{{url('/depenses/type')}}">
            <i class="menu-icon fa fa-circle-o"></i>
            Type
          </a>
          <b class="arrow"></b>
        </li>

        <li   rel="listdepense" class="listdepense" role="depense">
          <a href="{{url('/depenses/list')}}">
            <i class="menu-icon fa fa-circle-o "></i>
            Liste
          </a>

          <b class="arrow"></b>
        </li>


      </ul>
    </li>


    <li  rel="entreprise" class="entreprise">
      <a href="{{url('caissebanque')}}">
        <i class="menu-icon icon-institution bigger-130"></i>
        <span class="menu-text"> Caisse/Banque </span>
      </a>

      <b class="arrow"></b>
    </li>
  
    <li  rel="entreprise" class="entreprise">
      <a href="{{url('entreprises')}}">
        <i class="menu-icon icon-institution bigger-130"></i>
        <span class="menu-text"> Entreprise </span>
      </a>

      <b class="arrow"></b>
    </li>

    <li  rel="config" class="config">
      <a href="{{url('config')}}">
        <i class="menu-icon icon-cogs2 bigger-130"></i>
        <span class="menu-text"> Configuration </span>
      </a>
      <b class="arrow"></b>
    </li>
  </ul><!-- /.nav-list -->


</div>