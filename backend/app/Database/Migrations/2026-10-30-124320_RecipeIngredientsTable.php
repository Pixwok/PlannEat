<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RecipeIngredientsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_recipes' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'id_ingredients' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'quantity' => [
                'type' => 'FLOAT',
                'null' => false,
            ],
            'unit' => [
                'type' => 'ENUM',
                'constraint' => ['g', 'kg', 'ml', 'unité', 'c à soupe', 'c à café'],
                'default' => 'unité',
            ],
        ]);

        //Clé primaire = le couple est PK
        $this->forge->addKey(['id_recipes', 'id_ingredients'], true);

        // Clés étrangères
        $this->forge->addForeignKey('id_recipes', 'recipes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_ingredients', 'ingredients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('recipe_ingredients');
    }

    public function down()
    {
        $this->forge->dropTable('recipe_ingredients');
    }
}
