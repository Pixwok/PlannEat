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
            'required' => "Le nom de l'ingrédient est obligatoire.",
            'max_length' => 'Nom trop long (max 255)'
        ]
    ];

    
}
?>