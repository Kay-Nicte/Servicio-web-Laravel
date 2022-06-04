<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

use App\Http\Resources\ProductResource as ProductResource;

use Validator;

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
        if ($producte == null) {
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

    public function store(Request $request)

    {
        // En $input guardem totes les dades que s'han enviat via POST
        $input = $request->all();

        // Creem un validador de les dades enviades, i li passem les regles
        // que volem comprovar
        $validator = Validator::make($input, [
            'nom' => 'required|max:25',
            'preu' => 'required|numeric|min:0',
            'descripcio' => 'required|max:256',
        ]);

        // Si alguna dada no és correcta
        if ($validator->fails()) {

            $response = [
                'success' => false,
                'message' => "Alta incorrecta!",
                'data' => $validator->errors(),
            ];
            // Retornem l'array convertit a JSON i el codi d'error 404 de
            //  HTTTP
            return response()->json($response, 404);
        }


        $producte = Product::create($input);

        // Responem a la crida amb un tot ok!
        $response = [
            'success' => true,
            'data'    => $producte,
            'message' => "Alta correcta",

        ];

        return response()->json($response, 200);
    }

    public function create()
    {
        // Cridem a una vista simple anomenenada newFormProduct
        // Serà una vista html creada per poder fer una crida POST d'exemple
        // En una aplicació real la vista contindria javascript per poder
        // recuperar el
        // json retornat, per comprovar errors de validació ...
        return view('productes.newFormProduct');
    }

    public function destroy($id)
    {
        // Eliminem el producte segons el codi que ens passin

        // El busquem a la BD
        $producte = Product::find($id);

        // Si no trobem el producte responem amb informació
        // sobre l'error
        if ($producte == null) {
            $response = [
                'success' => false,
                'message' => "Producte no trobat",
            ];
            return response()->json($response, 404);
        } else { // El producte l'hem trobat

            // posar dins try-catch en cas de haver-hi relacions clau forana!!
            $producte->delete();

            $response = [
                'success' => true,
                'data'    => $producte,
                'message' => "Producte esborrat",
            ];

            return response()->json($response, 200);
        }
    }

    public function edit($id)
    {
        // No ho implementarem
        // Seria per si volem carregar un formulari per actualitzar un
        // producte determinat.
    }


    public function update(Request $request, $id)
    {
        // Aquí hi aniria el codi per actualitzar a la BD un
        //producte determinat amb la informació enviada via POST

        // Primer hauriem de recuperar el producte que volem actualizar
        // Sabem el seu id
        $producte = Product::find($id);

        // i actualitzar les seves dades segons $request
        // El codi serà semblant al del mètode store()
        $validator = Validator::make($request->all(), [
            'nom' => 'required|max:25',
            'preu' => 'required|numeric|min:0',
            'descripcio' => 'required|max:256',
        ]);

        // Si alguna dada no és correcta
        if ($validator->fails()) {

            $response = [
                'success' => false,
                'message' => "Actualització incorrecta!",
                'data' => $validator->errors(),
            ];
            // Retornem l'array convertit a JSON i el codi d'error 404 de HTTTP
            return response()->json($response, 404);
        }

        $producte->nom = $request->nom;
        $producte->preu = $request->preu;
        $producte->descripcio = $request->descripcio;
        $producte->save();

        // Responem a la crida amb un tot ok!
        $response = [
            'success' => true,
            'data' => $producte,
            'message' => "Actualització correcta",

        ];

        return response()->json($response, 200);
    }
}
