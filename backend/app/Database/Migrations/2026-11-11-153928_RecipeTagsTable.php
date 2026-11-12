<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RecipeTagsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_recipes' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'id_tags' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
        ]);

        //Clé primaire = le couple est PK
        $this->forge->addKey(['id_recipes', 'id_tags'], true);

        // Clés étrangères
        $this->forge->addForeignKey('id_recipes', 'recipes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_tags', 'tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('recipe_tags');
    }

    public function down()
    {
        $this->forge->dropTable('recipe_tags');
    }
}
