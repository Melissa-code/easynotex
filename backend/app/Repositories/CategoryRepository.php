<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CategoryRepository
{
    public function getCategoriesInAlphabeticalOrder(): Collection
    {
        try {
            return Cache::remember('categories_alphabetical', 60, function () {
                return DB::table('categories')
                    ->select('categories.*')
                    ->orderBy('categories.name', 'asc')
                    ->get();
            });
        } catch (QueryException $e) {
            Log::error("Erreur SQL dans le controller getCategories()", [
                'error' => $e->getMessage()
            ]);
            return collect([]);
        }
    }
}
