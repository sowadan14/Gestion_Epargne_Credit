<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\CmdeAchatController;
use App\Http\Controllers\CmdeVenteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\ModePaiementController;
use App\Http\Controllers\TypeProduitController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\ParamsController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\CategProduitController;
use App\Http\Controllers\RecepAchatController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\StockProduitController;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\FactureAchatController;
use App\Http\Controllers\PaiementAchatController;
use App\Http\Controllers\FactureVenteController;
use App\Http\Controllers\PaiementVenteController;
use App\Http\Controllers\LivrVenteController;
use App\Http\Controllers\AvoirFrController;
use App\Http\Controllers\AvoirCltController;
use App\Http\Controllers\EtatFrController;
use App\Http\Controllers\EtatCltController;
use App\Http\Controllers\ConvertStockController;
use App\Http\Controllers\RegulStockController;
use App\Http\Controllers\RegulCompteController;
use App\Http\Controllers\TypeDepenseController;
use App\Http\Controllers\CaisseBanqueController;
use App\Http\Controllers\DepenseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', '\App\Http\Controllers\HomeController@index');


Route::get('/home', '\App\Http\Controllers\HomeController@index');

Route::get('home1', '\App\Http\Controllers\HomeController@index');

Route::get('Tables', '\App\Http\Controllers\HomeController@List');

Route::get('login', function () {
    return view('auth.login');
});

Route::group(['middleware' => ['auth']], function() {
  
    Route::group(['prefix' => '/config'], function(){
        Route::resource('postes',PosteController::class);
        Route::resource('roles',RoleController::class);
        Route::resource('employes', EmployeController::class);
        Route::resource('comptes', CompteController::class);
        Route::resource('unites', UniteController::class);
        Route::resource('mpaiements', ModePaiementController::class);
        Route::resource('tprods', TypeProduitController::class);
        Route::resource('users', UserController::class);
        Route::resource('params', ParamsController::class);
        Route::resource('categprods', CategProduitController::class);
        Route::resource('prodts', ProduitController::class);
       
    });

    Route::group(['prefix' => '/stock'], function(){
        Route::resource('stockprodts',StockProduitController::class);
        Route::resource('convertstocks',ConvertStockController::class);
    });

    Route::group(['prefix' => '/regul'], function(){
        Route::resource('regulstocks',RegulStockController::class);
        Route::resource('regulcomptes',RegulCompteController::class);
    });


    Route::group(['prefix' => '/depenses'], function(){
        Route::resource('type',TypeDepenseController::class);
        Route::resource('list',DepenseController::class);
    });

    Route::group(['prefix' => '/achat'], function(){
        Route::resource('alists', AchatController::class);
        Route::resource('acmdes',CmdeAchatController::class);
        Route::resource('receps',RecepAchatController::class);       
        Route::get('receps/addrecept/{id}', [RecepAchatController::class,'addrecept'])->name('receps.addrecept');
        Route::get('acmdes/cloturer/{id}', [CmdeAchatController::class,'cloturer'])->name('acmdes.cloturer');
        // Route::resource('receps',[RecepAchatController::class,'except'=>'create']);
    });

  

    Route::group(['prefix' => '/vente'], function(){
        Route::resource('vlists', VenteController::class);
        Route::resource('vcmdes',CmdeVenteController::class);
        Route::resource('livrs',LivrVenteController::class);       
        Route::get('livrs/addlivr/{id}', [LivrVenteController::class,'addlivr'])->name('livrs.addlivr');
        Route::get('vcmdes/cloturer/{id}', [CmdeVenteController::class,'cloturer'])->name('vcmdes.cloturer');
        // Route::resource('receps',[RecepAchatController::class,'except'=>'create']);
    });

    Route::group(['prefix' => '/recouv'], function(){
        Route::resource('apaiements',PaiementAchatController::class);
        Route::resource('afacts',FactureAchatController::class);
        Route::resource('vpaiements',PaiementVenteController::class);
        Route::resource('vfacts',FactureVenteController::class);
        Route::resource('avoirfrs',AvoirFrController::class);
        Route::resource('etatfrs',EtatFrController::class);
        Route::resource('etatclts',EtatCltController::class);
        Route::resource('avoirclts',AvoirCltController::class);
        Route::get('apaiements/addpaiement/{id}', [PaiementAchatController::class,'addpaiement'])->name('apaiements.addpaiement');
        Route::get('apaiements/facture/{id}', [PaiementAchatController::class,'facture'])->name('apaiements.facture');        
        Route::get('afacts/addfact/{id}', [FactureAchatController::class,'addfact'])->name('afacts.addfact');
        Route::get('vpaiements/addpaiement/{id}', [PaiementVenteController::class,'addpaiement'])->name('vpaiements.addpaiement');
        Route::get('vpaiements/facture/{id}', [PaiementVenteController::class,'facture'])->name('vpaiements.facture');        
        Route::get('vfacts/addfact/{id}', [FactureVenteController::class,'addfact'])->name('vfacts.addfact');
        // Route::resource('receps',[RecepAchatController::class,'except'=>'create']);
    });

    Route::post('achat/acmdes/getUnites', [CmdeAchatController::class, 'getUnites']);
    Route::post('achat/alists/getUnites', [AchatController::class, 'getUnites']);

    Route::post('regul/regulstocks/getUnites', [RegulStockController::class, 'getUnites']);

    Route::post('stock/convertstocks/getUnites', [ConvertStockController::class, 'getUnites']);

    Route::post('achat/acmdes/getFrAvoir', [CmdeAchatController::class, 'getFrAvoir']);
    Route::post('achat/alists/getFrAvoir', [AchatController::class, 'getFrAvoir']);

    Route::post('vente/vcmdes/getUnites', [CmdeVenteController::class, 'getUnites']);
    Route::post('vente/vlists/getUnites', [VenteController::class, 'getUnites']);

    Route::post('vente/vcmdes/getCltAvoir', [CmdeVenteController::class, 'getCltAvoir']);
    Route::post('vente/vlists/getCltAvoir', [VenteController::class, 'getCltAvoir']);

    Route::post('achat/receps/getDetailsRecept', [RecepAchatController::class, 'getDetailsRecept']);
    Route::post('achat/afacts/getDetailsfact', [FactureAchatController::class, 'getDetailsfact']);
    Route::post('recouv/apaiements/getDetailspaiement', [PaiementAchatController::class, 'getDetailspaiement']);

    Route::post('vente/livrs/getDetailsLivr', [LivrVenteController::class, 'getDetailsLivr']);
    Route::post('vente/vfacts/getDetailsfact', [FactureVenteController::class, 'getDetailsfact']);
    Route::post('recouv/vpaiements/getDetailspaiement', [PaiementVenteController::class, 'getDetailspaiement']);

    Route::resource('/config', ConfigController::class);
    Route::resource('/entreprises', EntrepriseController::class);
    Route::resource('/caissebanque', CaisseBanqueController::class);
    Route::resource('/clients', ClientController::class);
    Route::resource('/frs', FournisseurController::class);
    Route::get('caissebanque/details/{id?}', [CaisseBanqueController::class,'details'])->name('caissebanque.details'); 
    // Route::get('caissebanque/details/{id?}', [CaisseBanqueController::class,'details'])->name('caissebanque.details'); 

    // Route::get('achat/receps/create/{id}', 'RecepAchatController@create')->name('receps.create');
   
});




// Route::resource('postes', PosteController::class);

// Route::resource('users', UserController::class);

// Route::get('produits', '\App\Http\Controllers\ProduitController@index');

// Route::get('users', '\App\Http\Controllers\UserController@index');

// Route::get('postes', '\App\Http\Controllers\PosteController@index');

// Route::get('login', function () {
//     return view('auth.login');
// });


Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/login', [App\Http\Controllers\HomeController::class, 'logout'])->name('login');

Route::view('register','register');
Route::view('login','login');





Route::group(['prefix' => 'basic-ui'], function(){
    Route::get('accordions', function () { return view('pages.basic-ui.accordions'); });
    Route::get('buttons', function () { return view('pages.basic-ui.buttons'); });
    Route::get('badges', function () { return view('pages.basic-ui.badges'); });
    Route::get('breadcrumbs', function () { return view('pages.basic-ui.breadcrumbs'); });
    Route::get('dropdowns', function () { return view('pages.basic-ui.dropdowns'); });
    Route::get('modals', function () { return view('pages.basic-ui.modals'); });
    Route::get('progress-bar', function () { return view('pages.basic-ui.progress-bar'); });
    Route::get('pagination', function () { return view('pages.basic-ui.pagination'); });
    Route::get('tabs', function () { return view('pages.basic-ui.tabs'); });
    Route::get('typography', function () { return view('pages.basic-ui.typography'); });
    Route::get('tooltips', function () { return view('pages.basic-ui.tooltips'); });
});

Route::group(['prefix' => 'advanced-ui'], function(){
    Route::get('dragula', function () { return view('pages.advanced-ui.dragula'); });
    Route::get('clipboard', function () { return view('pages.advanced-ui.clipboard'); });
    Route::get('context-menu', function () { return view('pages.advanced-ui.context-menu'); });
    Route::get('popups', function () { return view('pages.advanced-ui.popups'); });
    Route::get('sliders', function () { return view('pages.advanced-ui.sliders'); });
    Route::get('carousel', function () { return view('pages.advanced-ui.carousel'); });
    Route::get('loaders', function () { return view('pages.advanced-ui.loaders'); });
    Route::get('tree-view', function () { return view('pages.advanced-ui.tree-view'); });
});

Route::group(['prefix' => 'forms'], function(){
    Route::get('basic-elements', function () { return view('pages.forms.basic-elements'); });
    Route::get('advanced-elements', function () { return view('pages.forms.advanced-elements'); });
    Route::get('dropify', function () { return view('pages.forms.dropify'); });
    Route::get('form-validation', function () { return view('pages.forms.form-validation'); });
    Route::get('step-wizard', function () { return view('pages.forms.step-wizard'); });
    Route::get('wizard', function () { return view('pages.forms.wizard'); });
});

Route::group(['prefix' => 'editors'], function(){
    Route::get('text-editor', function () { return view('pages.editors.text-editor'); });
    Route::get('code-editor', function () { return view('pages.editors.code-editor'); });
});

Route::group(['prefix' => 'charts'], function(){
    Route::get('chartjs', function () { return view('pages.charts.chartjs'); });
    Route::get('morris', function () { return view('pages.charts.morris'); });
    Route::get('flot', function () { return view('pages.charts.flot'); });
    Route::get('google-charts', function () { return view('pages.charts.google-charts'); });
    Route::get('sparklinejs', function () { return view('pages.charts.sparklinejs'); });
    Route::get('c3-charts', function () { return view('pages.charts.c3-charts'); });
    Route::get('chartist', function () { return view('pages.charts.chartist'); });
    Route::get('justgage', function () { return view('pages.charts.justgage'); });
});

Route::group(['prefix' => 'tables'], function(){
    Route::get('basic-table', function () { return view('pages.tables.basic-table'); });
    Route::get('data-table', function () { return view('pages.tables.data-table'); });
    Route::get('js-grid', function () { return view('pages.tables.js-grid'); });
    Route::get('sortable-table', function () { return view('pages.tables.sortable-table'); });
});

Route::get('notifications', function () {
    return view('pages.notifications.index');
});

Route::group(['prefix' => 'icons'], function(){
    Route::get('material', function () { return view('pages.icons.material'); });
    Route::get('flag-icons', function () { return view('pages.icons.flag-icons'); });
    Route::get('font-awesome', function () { return view('pages.icons.font-awesome'); });
    Route::get('simple-line-icons', function () { return view('pages.icons.simple-line-icons'); });
    Route::get('themify', function () { return view('pages.icons.themify'); });
});

Route::group(['prefix' => 'maps'], function(){
    Route::get('vector-map', function () { return view('pages.maps.vector-map'); });
    Route::get('mapael', function () { return view('pages.maps.mapael'); });
    Route::get('google-maps', function () { return view('pages.maps.google-maps'); });
});

Route::group(['prefix' => 'user-pages'], function(){
    Route::get('login', function () { return view('pages.user-pages.login'); });
    Route::get('login-2', function () { return view('pages.user-pages.login-2'); });
    Route::get('multi-step-login', function () { return view('pages.user-pages.multi-step-login'); });
    Route::get('register', function () { return view('pages.user-pages.register'); });
    Route::get('register-2', function () { return view('pages.user-pages.register-2'); });
    Route::get('lock-screen', function () { return view('pages.user-pages.lock-screen'); });
});

Route::group(['prefix' => 'error-pages'], function(){
    Route::get('error-404', function () { return view('pages.error-pages.error-404'); });
    Route::get('error-500', function () { return view('pages.error-pages.error-500'); });
});


// For Clear cache
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.user-pages.error-404');
})->where('page','.*');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
