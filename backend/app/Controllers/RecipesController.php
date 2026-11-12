<?php
namespace App\Controllers;

use App\Models\RecipeModel;
use App\Models\RecipeTagModel;

class RecipesController extends BaseController
{
    public function test()
    {
        $RecipesModels = new RecipeModel();
        $RecipeTagsModels = new RecipeTagModel();

        $resultat = $RecipesModels->getRecipesWithTags();

        dd($resultat);
    }
}
