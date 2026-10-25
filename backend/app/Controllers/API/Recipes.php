<?php
namespace App\Controllers\API;

use App\Models\RecipeModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Recipes extends ResourceController
{
    use ResponseTrait;
    
    protected $modelName = 'App\Models\RecipeModel';
    protected $format = 'json';
    
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
            $recipes = $this->model->like('name', $name);
        }

        $recipes = $this->model->orderBy($orderBy, $direction)
                              ->findAll($limit);

        return $this->respond($recipes);
    }

    /**
     * Récupère une recette
     * GET /api/recipes/{id}
     */
    public function show($id = null)
    {
        $recipe = $this->model->find($id);
        if (!$recipe) {
            return $this->failNotFound('Recipe not found');
        }
        return $this->respond($recipe);
    }

    /**
     * Créer une recette
     * POST /api/recipes
     */
    public function create()
    {
        $dataJSON = $this->request->getJSON();
        $data = (array) $dataJSON;

        //Validation des données
        $validation = \Config\Services::validation();
        $validation->setRules($this->model->validationRules, $this->model->validationMessages);

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        try {
            $this->model->insert($data);
            return $this->respondCreated($data);
        } catch (\Exception $e) {
            return $this->failServerError('Database error: ' . $e->getMessage());
        }
        
    }

    /**
     * Modifier une recette
     * PUT /api/recipes/{id}
     */
    public function update($id = null)
    {
        $dataJSON = $this->request->getJSON();
        $data = (array) $dataJSON;

        //Validation des données
        $validation = \Config\Services::validation();
        $validation->setRules(['name' => 'if_exist|required|max_length[255]'], ['name' => 'Name ne peut pas être vide']);

        if (!$this->model->find($id)) {
            return $this->failNotFound('Recipe not found');
        }

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        try {
            $this->model->update($id, $data);
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
        $recipe = $this->model->find($id);
        if (!$recipe) {
            return $this->failNotFound('Recipe not found');
        }

        $this->model->delete($id);
        return $this->respondDeleted($recipe);
    }


}

?>