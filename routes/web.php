<?php

use App\Http\Controllers\ProfileController;
use App\Models\UtilisateurHasProduit;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard/{year}/{month}', function ($year, $month) {
    // Votre logique de gestion du dashboard ici

    // Requête existante pour les quantités totales par jour
    $utilisateur_has_produits = UtilisateurHasProduit::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(quantite) as total_quantite'))
        ->where('action', 'vente')
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->groupBy(DB::raw('DATE(created_at)'))
        ->get();

    // Requête pour la somme des ventes par utilisateur
    $total_ventes_par_utilisateur = DB::table('utilisateur_has_produits as uhp')
        ->join('users as u', 'uhp.user_id', '=', 'u.id')
        ->join('produits as p', 'uhp.produit_id', '=', 'p.id')
        ->select(
            'uhp.user_id',
            'u.name as user_name',
            DB::raw('SUM(p.prixVente * uhp.quantite) as total_vente')
        )
        ->where('uhp.action', 'vente')
        ->when($year, function ($query) use ($year) {
            return $query->whereYear('uhp.created_at', $year);
        })
        ->when($month, function ($query) use ($month) {
            return $query->whereMonth('uhp.created_at', $month);
        })
        ->groupBy('uhp.user_id', 'u.name')
        ->get();

    // Requête pour les ventes totales par jour
    $ventes_totales_par_jour = DB::table('utilisateur_has_produits as uhp')
        ->join('produits as p', 'uhp.produit_id', '=', 'p.id')
        ->select(
            DB::raw('DATE(uhp.created_at) as date'),
            DB::raw('SUM(p.prixVente * uhp.quantite) as total_vente')
        )
        ->where('uhp.action', 'vente')
        ->when($year, function ($query) use ($year) {
            return $query->whereYear('uhp.created_at', $year);
        })
        ->when($month, function ($query) use ($month) {
            return $query->whereMonth('uhp.created_at', $month);
        })
        ->groupBy(DB::raw('DATE(uhp.created_at)'))
        ->orderBy(DB::raw('DATE(uhp.created_at)'), 'asc')
        ->get();

    // Requête pour les résultats
    $results = DB::table('utilisateur_has_produits as uhp')
        ->join('produits as p', 'uhp.produit_id', '=', 'p.id')
        ->select('p.reference as reference_produit', DB::raw('SUM(uhp.quantite) as total_quantite'))
        ->where('uhp.action', 'vente')
        ->when($year, function ($query) use ($year) {
            return $query->whereYear('uhp.created_at', $year);
        })
        ->when($month, function ($query) use ($month) {
            return $query->whereMonth('uhp.created_at', $month);
        })
        ->groupBy('uhp.produit_id', 'p.reference')
        ->orderByDesc('total_quantite')
        ->get();

    // Requête pour les ventes par mois par utilisateur
    $ventes_par_mois_par_utilisateur = DB::table('utilisateur_has_produits as uhp')
        ->join('users as u', 'uhp.user_id', '=', 'u.id')
        ->select(
            'u.id as user_id',
            'u.name as user_name',
            DB::raw('YEAR(uhp.created_at) as year'),
            DB::raw('MONTH(uhp.created_at) as month'),
            DB::raw('SUM(uhp.quantite) as total_quantite')
        )
        ->where('uhp.action', 'vente')
        ->when($year, function ($query) use ($year) {
            return $query->whereYear('uhp.created_at', $year);
        })
        ->when($month, function ($query) use ($month) {
            return $query->whereMonth('uhp.created_at', $month);
        })
        ->groupBy('u.id', 'u.name', DB::raw('YEAR(uhp.created_at)'), DB::raw('MONTH(uhp.created_at)'))
        ->orderBy('u.id')
        ->orderBy(DB::raw('YEAR(uhp.created_at)'))
        ->orderBy(DB::raw('MONTH(uhp.created_at)'))
        ->get();
    $poucentageLaitCereale = DB::table('utilisateur_has_produits as uhp')
        ->join('produits as p', 'uhp.produit_id', '=', 'p.id')
        ->selectRaw("
            ROUND(SUM(CASE WHEN p.reference LIKE '%lait%' THEN uhp.quantite ELSE 0 END) * 100 / SUM(uhp.quantite), 2) AS Laits,
            ROUND(SUM(CASE WHEN p.reference LIKE '%cereal%' THEN uhp.quantite ELSE 0 END) * 100 / SUM(uhp.quantite), 2) AS Cereale
        ")
        ->whereYear('uhp.created_at', $year)
        ->whereMonth('uhp.created_at', $month)
        ->get();

    $pourcentageCerealesVendues = DB::table('utilisateur_has_produits as uh')
        ->join('produits as p', 'uh.produit_id', '=', 'p.id')
        ->selectRaw('(SUM(uh.quantite) / (SELECT SUM(quantite) FROM utilisateur_has_produits WHERE action = ?)) * 100 AS pourcentage_cereales_vendues', ['vente'])
        ->where('uh.action', 'vente')
        ->where('p.reference', 'like', '%CEREALES%')
        ->whereYear('uh.created_at', $year)
        ->whereMonth('uh.created_at', $month)
        ->first();

    $pourcentageLaitInfantile = DB::table('utilisateur_has_produits as uh')
        ->join('produits as p', 'uh.produit_id', '=', 'p.id')
        ->selectRaw('(SUM(uh.quantite) / (SELECT SUM(quantite) FROM utilisateur_has_produits WHERE action = "vente")) * 100 AS pourcentage_Lait_infantile')
        ->where('uh.action', '=', 'vente')
        ->where('p.reference', 'like', '%LAIT INF%')
        ->whereYear('uh.created_at', $year)
        ->whereMonth('uh.created_at', $month)
        ->first();

    $pourcentageLaitDeCroissance = DB::table('utilisateur_has_produits as uh')
        ->join('produits as p', 'uh.produit_id', '=', 'p.id')
        ->selectRaw('(SUM(uh.quantite) / (SELECT SUM(quantite) FROM utilisateur_has_produits WHERE action = "vente")) * 100 AS pourcentage_Lait_de_croissance')
        ->where('uh.action', '=', 'vente')
        ->where('p.reference', 'like', '%LAIT DE CROISSANCE %')
        ->whereYear('uh.created_at', $year)
        ->whereMonth('uh.created_at', $month)
        ->first();

    $quantitesVenduesParMois = DB::table('utilisateur_has_produits as uh')
        ->selectRaw('YEAR(uh.created_at) as annee, MONTH(uh.created_at) as mois, SUM(uh.quantite) as quantite_totale')
        ->where('uh.action', '=', 'vente')
        ->whereYear('uh.created_at', $year)
        ->groupBy(DB::raw('YEAR(uh.created_at), MONTH(uh.created_at)'))
        ->orderByRaw('YEAR(uh.created_at) ASC, MONTH(uh.created_at) ASC')
        ->get();

    $quantitesVenduesMoisEnCours = DB::table('utilisateur_has_produits as uh')
        ->selectRaw('YEAR(uh.created_at) as annee, MONTH(uh.created_at) as mois, SUM(uh.quantite) as quantite_totale')
        ->where('uh.action', '=', 'vente')
        ->whereYear('uh.created_at', $year)
        ->whereMonth('uh.created_at', $month)
        ->groupBy(DB::raw('YEAR(uh.created_at), MONTH(uh.created_at)'))
        ->orderByRaw('YEAR(uh.created_at) ASC, MONTH(uh.created_at) ASC')
        ->get();

    $sommeTotale = DB::table('utilisateur_has_produits as uhp')
        ->join('produits as p', 'uhp.produit_id', '=', 'p.id')
        ->where('uhp.action', 'vente')
        ->whereYear('uhp.created_at', $year)
        ->whereMonth('uhp.created_at', $month)
        ->sum(DB::raw('uhp.quantite * p.prixVente'));

    // Récupérer les années et les mois distincts de la table utilisateur_has_produits
    $anneesMois = DB::table('utilisateur_has_produits')
    ->select(DB::raw('YEAR(created_at) as annee, MONTH(created_at) as mois'))
    ->distinct()
    ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
    ->orderBy(DB::raw('MONTH(created_at)'), 'desc')
    ->get();


    return inertia('Dashboard', [
        'utilisateur_has_produits' => $utilisateur_has_produits,
        'total_ventes_par_utilisateur' => $total_ventes_par_utilisateur,
        'ventes_totales_par_jour' => $ventes_totales_par_jour,
        'results' => $results,
        'ventes_par_mois_par_utilisateur' => $ventes_par_mois_par_utilisateur,
        'poucentageLaitCereale' => $poucentageLaitCereale,
        'pourcentageCerealesVendues' => $pourcentageCerealesVendues,
        'pourcentageLaitInfantile' => $pourcentageLaitInfantile,
        'pourcentageLaitDeCroissance' => $pourcentageLaitDeCroissance,
        'quantitesVenduesParMois' => $quantitesVenduesParMois,
        'quantitesVenduesMoisEnCours' => $quantitesVenduesMoisEnCours,
        'sommeTotale' => $sommeTotale,
        'anneesMois' => $anneesMois,
    ]);

    // return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';