<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;


use App\Http\Resources\ProductResource as ProductResource;


class ProductController extends Controller
{
    //
    public function index()
    {

        // Obtenim el llistat de tots els productes
        $productes = Product::all();

        // Es podria retornar directament l'array d'objectes $productes
        // Laravel al fer el return ho converteix directament a format JSON

        // return $productes;

        // Però construirem un JSON una mica més elaborat
        // Definim un array associatiu anomenat response
        // amb diferents posicions
        $response = [
        'success' => true,  // Per indicar que Tot ha anat bé
        'message' => "Llista productes recuperada", // missatge
        'data' => ProductResource::collection($productes), // en data posem la llista de productes
        ];

        // convertim l'array associatiu a format JSON i retornem STATUS 200,
        // és a dir, tot ok!
        return response()->json($response, 200);
    }

    public function show($id)
    {
        // Buscar un producto por su ID
        $producte = Product::find($id);

        // Si no se ha encontrado ningún producto
        if ($producte==null) {
            $response = [
            'success' => false,
            'message' => "Producte no trobat",
            ];
            return response()->json($response, 404);
        } else { // Si el producto se encuentra

            $response = [
            'success' => true,
            'data'    => new ProductResource($producte),
            'message' => "Producte recuperat",
            ];
            return response()->json($response, 200);
        }
    }

    public function productesPreuInferior($preu)
    {
        // Buscar productos con un precio inferior
        $productes = Product::where('preu', '<', $preu)->get();

        // Si el producto no se encuentra
        if ($productes->isEmpty()) {
            $response = [
                'success' => false,
                'message' => "Producte no trobat",
            ];
            return response()->json($response, 404);

        } else {
            // Si el producto se encuentra
            $response = [
                'success' => true,
                'data' => $productes,
                'message' => "Producte recuperat",
            ];
            return response()->json($response, 200);
        }
    }
}
