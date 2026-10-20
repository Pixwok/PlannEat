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

    /**
     * Liste les recettes
     * @param int $limit 
     * @param string $orderBy
     * @param string $direction Par defaut ASC
     * @return array Liste des recettes 
     */
    public function getRecipes($limit = null, $orderBy = 'id', $direction = 'ASC')
    {
        return $this->orderBy($orderBy,$direction)
                    ->findAll($limit);
    }

    /**
     * Récupère un recette
     * @param array $filter Filtre de recherche
     * @return array 
     */
    public function searchRecipes($filter)
    {
        return $this->where($filter)->findAll();
    }

    /**
     * Ajout d'une recette
     * @param array $data
     * @return int ID recette créée
     */
    public function createRecipe($data)
    {
        $this->insert($data);
        return $this->insertID();
    }

    /**
     * Modification d'une recette
     * @param int $id ID de la recette
     * @param array $data
     * 
     */
    public function editRecipe($id, $data)
    {
        $this->update($id, $data);
    }

    /**
     * Suppression d'une recette
     * @param int $id ID recette
     */
    public function deleteRecipes($id)
    {
        $this->delete($id);
    }
}
?>
