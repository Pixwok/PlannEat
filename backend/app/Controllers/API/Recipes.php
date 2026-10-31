<?php
namespace App\Controllers\API;

use App\Models\RecipeIngredientModel;
use App\Models\RecipeModel;
use App\Models\RecipeStepModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Recipes extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';
    
    protected $recipeModel;
    protected $stepModel;
    protected $ingredientModel;

    public function __construct()
    {
        $this->recipeModel = new RecipeModel();
        $this->stepModel = new RecipeStepModel();
        $this->ingredientModel = new RecipeIngredientModel();
    }

    /**
     * Liste une ou plusieurs recettes
     * GET /api/recipes
     * GET /api/recipes?name="name_recette"
     */
    public function index()
    {
        $limit = $this->request->getGet('limit');
        $orderBy = $this->request->getGet('order_by') ?? 'id';
        $direction = $this->request->getGet('direction') ?? 'ASC';
        $name = $this->request->getGet('name');


        if (!empty($name)) {
            $recipes = $this->recipeModel->like('name', $name);
        }

        $recipes = $this->recipeModel->orderBy($orderBy, $direction)
                              ->findAll($limit);

        return $this->respond($recipes);
    }

    /**
     * Récupère une recette
     * GET /api/recipes/{id}
     */
    public function show($id = null)
    {
        $recipe = $this->recipeModel->find($id);
        if (!$recipe) {
            return $this->failNotFound('Recipe not found');
        }

        // Récupération des ingrédients
        $ingredients = $this->recipeModel->getIngredients($id);
        // Récupération des étapes
        $steps = $this->recipeModel->getSteps($id);

        // Formatage JSON
        $respond = [
            'recipe' => $recipe,
            'steps' => $steps,
            'ingredients' => $ingredients
        ];

        return $this->respond($respond);
    }

    /**
     * Créer une recette
     * POST /api/recipes
     */
    public function create()
    {
        $data = $this->request->getJSON(true);

        //Validation des données
        $validation = \Config\Services::validation();
        $validation->setRules($this->recipeModel->validationRules, $this->recipeModel->validationMessages);

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        $this->recipeModel->db->transStart();

        try {
            // Création recette
            $recipeId = $this->recipeModel->insert([
                'name' => $data['name'],
                'note' => $data['note'] ?? null,
                'time' => $data['time'] ?? null
            ]);

            // Ajout des étapes
            foreach ($data['steps'] as $step) {
                $this->stepModel->insert([
                    'id_recipes' => $recipeId,
                    'step_number' => $step['step_number'],
                    'step_description' => $step['step_describe']
                ]);
            }
            // Ajout des ingrédients
            foreach ($data['ingredients'] as $ingredient) {
                $this->ingredientModel->insert([
                    'id_recipes' => $recipeId,
                    'id_ingredients' => $ingredient['id_ingredients'],
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit']
                ]);
            }

            $this->recipeModel->db->transComplete();

            if ($this->recipeModel->db->transStatus() === false) {
                return $this->fail('Erreur lors de la création', 500);
            }

            return $this->respondCreated([
                'id' => $recipeId,
                'message' => 'Recette créée avec succés.'
            ]);
        } catch (\Exception $e) {
            $this->recipeModel->db->transRollback();
            return $this->failServerError('Database error: ' . $e->getMessage());
        }
        
    }

    /**
     * Modifier une recette
     * PUT /api/recipes/{id}
     */
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);

        //Validation des données
        $validation = \Config\Services::validation();
        $validation->setRules(['name' => 'if_exist|required|max_length[255]'], ['name' => 'Name ne peut pas être vide']);

        if (!$this->recipeModel->find($id)) {
            return $this->failNotFound('Recipe not found');
        }

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        $this->recipeModel->db->transStart();

        try {
            // Mise à jour recette
            if (isset($data['name']) || isset($data['time']) || isset($data['note'])) {
                $this->recipeModel->update($id, $data);
            }
            // Mise à jour des étapes
            if (isset($data['steps'])) {
                $this->stepModel->where('id_recipes', $id)->delete();
                foreach ($data['steps'] as $step) {
                    $this->stepModel->insert([
                        'id_recipes' => $id,
                        'step_number' => $step['step_number'],
                        'step_description' => $step['step_describe']
                    ]);
                }
            }

            // Mise à jour des ingrédients
            if (isset($data['ingredients'])) {
                $this->ingredientModel->where('id_recipes', $id)->delete();
                foreach ($data['ingredients'] as $ingredient) {
                    $this->ingredientModel->insert([
                        'id_recipes' => $id,
                        'id_ingredients' => $ingredient['id_ingredients'],
                        'quantity' => $ingredient['quantity'],
                        'unit' => $ingredient['unit']
                    ]);
                }
            }

            $this->recipeModel->db->transComplete();

            if ($this->recipeModel->db->transStatus() === false) {
                return $this->fail('Erreur lors de la création', 500);
            }

            return $this->respondUpdated($data);
        } catch (\Exception $e) {
            return $this->failServerError('Database error: ' . $e->getMessage());
        }
    }

    /**
     * Suppression recette
     * DELETE /api/recipes/{id}
     */
    public function delete($id = null)
    {
        $recipe = $this->recipeModel->find($id);
        if (!$recipe) {
            return $this->failNotFound('Recipe not found');
        }

        $this->stepModel->where('id_recipes', $id)->delete();
        $this->ingredientModel->where('id_recipes', $id)->delete();
        $this->recipeModel->delete($id);
        return $this->respondDeleted($recipe);
    }


}

?>