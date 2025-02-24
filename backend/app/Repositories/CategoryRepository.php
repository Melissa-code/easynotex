<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class CategoryRepository
{
    public function getCategoriesInAlphabeticalOrder(): Collection
    {
      try {
        return DB::table('categories')
          ->select(
              'categories.id',
              'categories.name',
          )
          ->orderBy('categories.name', 'asc')
          ->get();
      } catch (QueryException $e) {
          Log::error("Erreur SQL dans le controller getCategories()", [
              'error' => $e->getMessage()
          ]);
          return collect([]);
      }
    }
}
