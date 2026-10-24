<?php
namespace App\Models;

use CodeIgniter\Model;
use RuntimeException;

class IngredientModel extends Model
{
    protected $table = 'ingredients';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name'];

    protected $validationRules = [
        'name' => 'required|max_length[255]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Le nom ingrédient est obligatoire.',
            'max_length' => 'Nom trop long (max 255)'
        ]
    ];

    /**
     * Liste tous les ingrédients disponibles
     * @param int $limit Limite de la sortie
     * @param string $orderBy
     * @param string $direction
     * @return array Liste des ingrédients
     */
    public function getIngredients(?int $limit = null, string $orderBy = 'id', string $direction = 'ASC'): array
    {
        return $this->orderBy($orderBy,$direction)
                    ->findAll($limit);
    }

    /**
     * Récupère un ingrédient par son ID
     * @param int $id
     * @return array 
     */
    public function getIngredientById(int $id): array
    {
        return $this->find($id);
    }

    /**
     * Recherche un ingrédient
     * @param string $filterName 
     * @return mixed
     */
    public function searchIngredient(string $filterName): mixed
    {
        return $this->like('name', $filterName)->findAll();
    }

    /**
     * Création d'un ingrédient
     * @param array $data 
     * @return int ID ingrédient 
     */
    public function createIngredient(array $data): int
    {
        $res = $this->insert($data);
        if ($res === false) {
            throw new RuntimeException("Echec de création de l'ingrédient.");
        }
        return $this->insertID();
    }

    /**
     * Modification d'un ingrédient
     * @param int $id ID ingrédient
     * @param array $data 
     */
    public function editIngredient(int $id, array $data): void
    {
        if (! $this->update($id, $data)) {
            throw new RuntimeException("Echec de la modification de l'ingredient {$id}.");
        }
    }

    /**
     * Suppression d'un ingrédient
     * @param int $id ID ingrédient
     */
    public function deleteIngredient(int $id): void
    {
        $this->delete($id);
    }
}
?>