<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}

    public function getCategories(): Collection
    {
        return $this->categoryRepository->getCategoriesInAlphabeticalOrder();
    }
}
