<?php
namespace App\Controllers;

use App\Models\RecipeModel;

class RecipesController extends BaseController
{
    public function test()
    {
        $RecipesModels = new RecipeModel();

        $resultat = $RecipesModels->getRecipe(['id' => 2]);
        dd($resultat);
    }
}
