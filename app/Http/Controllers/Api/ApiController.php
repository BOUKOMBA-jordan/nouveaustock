<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategorieResource;
use App\Http\Resources\ProduitResource;
use App\Http\Resources\UtilisateurHasProduitResource;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\User;
use App\Models\UtilisateurHasProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'statut' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return response()->json([
                'statut' => true,
                'message' => 'Utilisateur creer avec succès',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'statut' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    public function login(Request $request)
    {
        try {

            $validateUser = Validator::make($request->all(),
                [

                    'email' => 'required|email',
                    'password' => 'required',
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'statut' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            if (! Auth::attempt($request->only(['email', 'password']))) {

                return response()->json([
                    'statut' => false,
                    'message' => 'email & password does not match with our record',

                ], 401);

            }
            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'User Logged In succefully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'statut' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function categories()
    {
        try {

            $query = Categorie::query();
            $categories = $query->paginate(10)->onEachSide(1);

            /*return inertia('Admin/Categories', [
               "categories" => CategorieResource::collection($categories),
            ]);*/

            return response()->json([
                'Categories' => CategorieResource::collection($categories),

            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'statut' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    public function produits()
    {
        try {

            $query = Produit::query();
            $produits = $query->paginate(10)->onEachSide(1);

            /*return inertia('Admin/Categories', [
               "categories" => CategorieResource::collection($categories),
            ]);*/

            return response()->json([
                'produits' => ProduitResource::collection($produits),

            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'statut' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    
    public function enregistreProduit(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'user_id' => 'required',
                    'reference' => 'required',
                    'action' => 'required',
                    'quantite' => 'required',
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'statut' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            };
            
            // Requête pour récupérer l'id du produit en fonction de la référence
            $produit = Produit::where('reference', $request->reference)->first();

             if ($produit) {
                //return response()->json(['id' => $produit->id]);

                $utilisateurHasProduit = UtilisateurHasProduit::create([
                    'user_id' => $request->user_id,
                    'produit_id' => $produit->id,
                    'action' => $request->action,
                    'quantite' => $request->quantite,
                ]);

                
                return response()->json([
                    'statut' => true,
                    'message' => 'produit creer avec succès',
                    'utilisateurHasProduit' => $utilisateurHasProduit,
                ], 200);
    

                
            } else {
                return response()->json(['error' => 'Produit non trouvé'], 404);
                
            }

            
          

        } catch (\Throwable $th) {
            return response()->json([
                'statut' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

    }
}