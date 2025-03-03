<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {
    }

    /**
     * Get all the categories
     *
     * @return Collection
     * @throws Exception
     */
    public function getCategories(): Collection
    {
        try {
            return $this->categoryRepository->getCategoriesInAlphabeticalOrder();
        } catch (Exception $e) {
            Log::error("Erreur dans CategoryService getCategories() " . $e->getMessage());
            throw $e;
        }
    }
}
