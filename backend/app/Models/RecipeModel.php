<?php
namespace App\Models;

use CodeIgniter\Model;

class RecipeModel extends Model
{
    protected $table = 'recipes';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'note', 'time'];

    protected $useTimestamps = true;

    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[255]'
    ];

    protected $validationMessages = [
        'name' => ['required' => 'Le nom de la recette est obligatoire.'],
    ];

    /**
     * Récupérer les recettes avec leurs tags
     */
    public function getRecipesWithTags()
    {
        return $this->select('recipes.*, recipe_tags.id_tags, tags.name AS tags')
            ->join('recipe_tags', 'recipe_tags.id_recipes=recipes.id', 'left')
            ->join('tags', 'tags.id=recipe_tags.id_tags', 'left')
            ->findAll();
    }

    /**
     * Récupération des ingrédients de la recette
     * @param int $recipeId
     */
    public function getIngredients(int $recipeId)
    {
        return $this->db->table('recipe_ingredients')
            ->select('ingredients.id AS id_ingredients, ingredients.name, recipe_ingredients.quantity, recipe_ingredients.unit')
            ->join('ingredients', 'ingredients.id=recipe_ingredients.id_ingredients')
            ->where('id_recipes', $recipeId)
            ->get()->getResultArray();
    }

    /**
     * Récupère les étapes de la recette
     * @param int $recipeId
     */
    public function getSteps(int $recipeId)
    {
        return $this->db->table('recipe_steps')
            ->select('*')
            ->where('id_recipes', $recipeId)
            ->orderBy('step_number', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Récupère les tags de la recette
     * @param int $recipeId
     */
    public function getTags(int $recipeId)
    {
        return $this->db->table('recipe_tags')
            ->select('tags.id AS id_tags, tags.name')
            ->join('tags', 'tags.id=recipe_tags.id_tags')
            ->where('id_recipes', $recipeId)
            ->get()->getResultArray();
    }
}
?>
