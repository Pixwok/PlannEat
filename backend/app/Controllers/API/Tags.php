<?php
namespace App\Controllers\API;

use App\Model\TagModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Tags extends ResourceController
{
    use ResponseTrait;

    protected $modelName = 'App\Models\TagModel';
    protected $format = 'json';

    /**
     * Liste des tags
     * GET /api/tags
     */
    public function index()
    {
        $limit = $this->request->getGet('limit');
        $orderBy = $this->request->getGet('order_by') ?? 'id';
        $direction = $this->request->getGet('direction') ?? 'ASC';
        $name = $this->request->getGet('name');


        if (!empty($name)) {
            $tags = $this->model->like('name', $name);
        }

        $tags = $this->model->orderBy($orderBy, $direction)
                              ->findAll($limit);

        return $this->respond($tags);
    }

    /**
     * Récupération d'un tag
     * GET /api/tags/{id}
     */
    public function show($id = null)
    {
        $tags = $this->model->find($id);

        if (!$tags) {
            return $this->failNotFound('Tags not found');
        }
        return $this->respond($tags);
    }

    /**
     * Création d'un tag
     * POST /api/tags
     */
    public function create()
    {
        $data = $this->request->getJSON(true);

        //Validation des données
        $validation = \Config\Services::validation();
        $validation->setRules($this->model->validationRules, $this->model->validationMessages);

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        try {
            $this->model->insert($data);
            return $this->respondCreated($this->model->insertID());
        } catch (\Exception $e) {
            return $this->failServerError('Database error: ' . $e->getMessage());
        }
    }

    /**
     * Modifier un tag
     * /PUT /api/tags/{id}
     */
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        $tags = $this->model->find($id);

        if (!$tags) {
            return $this->failNotFound('Tags not found');
        }

        //Validation des données
        $validation = \Config\Services::validation();
        $validation->setRules($this->model->validationRules, $this->model->validationMessages);

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
     * Suppression d'un tags
     * DELETE /api/tags/{id}
     */
    public function delete($id = null)
    {
        $tags = $this->model->find($id);
        if (!$tags) {
            return $this->failNotFound('Tags not found');
        }

        $this->model->delete($id);
        return $this->respondDeleted($tags);
    }
}
?>