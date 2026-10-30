<?php
namespace App\Models;

use CodeIgniter\Model;

class RecipeIngredientModel extends Model
{
    protected $table = 'recipe_ingredients';
    protected $allowedFields = ['id_recipes', 'id_ingredients', 'quantity', 'unit'];
}
?>