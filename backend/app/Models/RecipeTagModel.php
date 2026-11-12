<?php
namespace App\Models;

use CodeIgniter\Model;

class RecipeTagModel extends Model
{
    protected $table = 'recipe_tags';

    protected $allowedFields = ['id_recipes', 'id_tags'];
}
?>