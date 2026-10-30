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
     * Récupération des ingrédients de la recette
     * @param int $recipeId
     */
    public function getIngredients(int $recipeId)
    {
        return $this->db->table('recipe_ingredients')
            ->select('')
            ->join('ingredients', 'ingredients.id=recipes_ingredients.id_ingredients')
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
            ->select('')
            ->where('id_recipes', $recipeId)
            ->orderBy('step_number', 'ASC')
            ->get()->getResultArray();
    }
}
?>
