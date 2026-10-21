<?php
namespace App\Models;

use CodeIgniter\Model;
use RuntimeException;

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
     * Liste les recettes
     * @param int $limit 
     * @param string $orderBy
     * @param string $direction Par defaut ASC
     * @return array Liste des recettes 
     */
    public function getRecipes(?int $limit = null, string $orderBy = 'id', string $direction = 'ASC'): array
    {
        return $this->orderBy($orderBy,$direction)
                    ->findAll($limit);
    }

    /**
     * Récupère une recette par son ID
     * @param int $îd
     * @return array
     */
    public function getRecipeById(int $id): array
    {
        return $this->find($id);
    }

    /**
     * Récupère un recette
     * @param array $filter Filtre de recherche
     * @return mixed 
     */
    public function searchRecipes(array $filter): array
    {
        return $this->where($filter)->findAll();
    }

    /**
     * Ajout d'une recette
     * @param array $data
     * @return int ID recette créée
     */
    public function createRecipe(array $data): int
    {
        $res = $this->insert($data);
        if ($res === false) {
            throw new RuntimeException("Echec de création de la recette.");
        }
        return $this->insertID();
    }

    /**
     * Modification d'une recette
     * @param int $id ID de la recette
     * @param array $data
     */
    public function editRecipe(int $id, array $data): void
    {
        if (! $this->update($id, $data)) {
            throw new RuntimeException("Echec de la modification de la recette {$id}.");
        }
    }

    /**
     * Suppression d'une recette
     * @param int $id ID recette
     */
    public function deleteRecipes(int $id): void
    {
        $this->delete($id);
    }
}
?>
