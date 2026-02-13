<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: "/api/books",
        summary: "Liste des livres",
        responses: [
            new OA\Response(response: 200, description: "Succès")
        ]
    )]
    public function index()
    {
        $books = Book::paginate(2);
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: "/api/books",
        summary: "Créer un livre",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title","author","summary","isbn"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Nouveau titre unique"),
                    new OA\Property(property: "author", type: "string", example: "Nouvel auteur unique"),
                    new OA\Property(property: "summary", type: "string", example: "Nouveau sommaire unique"),
                    new OA\Property(property: "isbn", type: "string", example: "9780451524910")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Livre créé"),
            new OA\Response(response: 422, description: "Erreur de validation")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:100',
            'summary' => 'required|string|min:10|max:500',
            'isbn' => 'required|string|size:13|unique:books,isbn',
        ]);

        $book = Book::create($validated);
        return new BookResource($book);
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: "/api/books/{book}",
        summary: "Détails d'un livre",
        parameters: [
            new OA\Parameter(name: "book", in: "path", required: true, description: "ID du livre", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Succès"),
            new OA\Response(response: 404, description: "Livre non trouvé")
        ]
    )]
    public function show(string $id)
    {
        $book = Cache::remember("book_$id", 60*60, function() use ($id) {
            return Book::findOrFail($id);
        });
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: "/api/books/{book}",
        summary: "Mettre à jour un livre",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "book", in: "path", required: true, description: "ID du livre", schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Nouveau titre"),
                    new OA\Property(property: "author", type: "string", example: "Nouvel auteur"),
                    new OA\Property(property: "summary", type: "string", example: "Nouveau sommaire"),
                    new OA\Property(property: "isbn", type: "string", example: "9780451524999")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Livre mis à jour"),
            new OA\Response(response: 404, description: "Livre non trouvé"),
            new OA\Response(response: 422, description: "Erreur de validation")
        ]
    )]
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|min:3|max:255',
            'author' => 'sometimes|required|string|min:3|max:100',
            'summary' => 'sometimes|required|string|min:10|max:500',
            'isbn' => 'sometimes|required|string|size:13|unique:books,isbn,' . $book->id,
        ]);

        $book->update($validated);
        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: "/api/books/{book}",
        summary: "Supprimer un livre",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "book", in: "path", required: true, description: "ID du livre", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 204, description: "Livre supprimé"),
            new OA\Response(response: 404, description: "Livre non trouvé")
        ]
    )]
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->noContent();
    }
}
