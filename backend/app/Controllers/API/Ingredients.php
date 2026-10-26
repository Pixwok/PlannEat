<?php
namespace App\Controllers\API;

use App\Models\IngredientModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Ingredients extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\IngredientModel';
    protected $format = 'json';

    /**
     * GET /api/ingredients
     * GET /api/ingredients?name="name_ingredient"
     */
    public function index()
    {
        $limit = $this->request->getGet('limit');
        $orderBy = $this->request->getGet('order_by') ?? 'id';
        $direction = $this->request->getGet('direction') ?? 'ASC';
        $name = $this->request->getGet('name');


        if (!empty($name)) {
            $ingredients = $this->model->like('name', $name);
        }

        $ingredients = $this->model->orderBy($orderBy, $direction)
                              ->findAll($limit);

        return $this->respond($ingredients);
    }

    /**
     * GET /api/ingredients/{id}
     */
    public function show($id = null)
    {
        $ingredient = $this->model->find($id);
        if (!$ingredient) {
            return $this->failNotFound('Ingredient not found');
        }
        return $this->respond($ingredient);
    }

    /**
     * POST /api/ingredients
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
     * PUT /api/ingredients/{id}
     */
    public function update($id = null)
    {
        $dataJSON = $this->request->getJSON();
        $data = (array) $dataJSON;

        //Validation des données
        $validation = \Config\Services::validation();
        $validation->setRules(['name' => 'if_exist|required|max_length[255]'], ['name' => 'Name ne peut pas être vide']);

        if (!$this->model->find($id)) {
            return $this->failNotFound('Ingredient not found');
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
     * DELETE /api/recipes/{id}
     */
    public function delete($id = null)
    {
        $ingredient = $this->model->find($id);
        if (!$ingredient) {
            return $this->failNotFound('Ingredient not found');
        }

        $this->model->delete($id);
        return $this->respondDeleted($ingredient);
    }
}
?>