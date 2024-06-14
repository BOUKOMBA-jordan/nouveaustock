<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UtilisateurHasProduit;
use Illuminate\Http\Request;

class UtilisateurHasProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = UtilisateurHasProduit::query();
        $utilisateur_has_produits = $query->paginate(10)->onEachSide(1);
        return inertia('Admin/UtilisateurHasProduit', [
           "UtilisateurHasProduit" => UtilisateurHasProduit::collection($utilisateur_has_produits),
        ]);

        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $produits = new UtilisateurHasProduit();
        
        $produits->user_id = $request->input('user_id');
        $produits->produit_id = $request->input('produit_id');
        $produits->action = $request->input('action');
        $produits->quantite = $request->input('quantite');
        
        $produits->save();
        return response()->json($produits);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}