<?php
namespace App\Models;

use CodeIgniter\Model;

class RecipeStepModel extends Model
{
    protected $table = 'recipe_steps';
    protected $primaryKey = 'id';

    protected $allowedFields = ['step_description', 'step_number', 'id_recipes'];

    protected $validationRules = [
        'step_description' => 'required|max_length[255]',
        'step_number' => 'required|integer',
        'id_recipes' => 'required|integer',
    ];

    protected $validationMessages = [
        'step_description' => ['required' => 'La description ne peux pas être vide'],
        'step_number' => [
            'required' => "Le numéro de l'étape est obligatoire.",
            'integer' => "Doit être un entier",
        ],
        'id_recipes' => [
            'required' => "ID de la recette requis",
            'integer' => "Doit être un entier",
        ],
    ];
}
?>