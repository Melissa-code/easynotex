<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {
    }

    /**
     * Get all the categories
     *
     * @return JsonResponse
      */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getCategories();

            if ($categories->isEmpty()) {
                Log::warning("Aucune catégorie trouvée.");
                return response()->json(['message' => 'Aucune catégorie trouvée.'], 404);
            }

            return response()->json(['categories' => $categories], 200);

        } catch (Exception $e) {
            Log::error("Erreur lors de la récupération des catégories : " . $e->getMessage());

            return response()->json([
                'message' => "Une erreur est survenue lors de la récupération des catégories.",
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
