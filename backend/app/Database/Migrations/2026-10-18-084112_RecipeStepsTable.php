<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RecipeStepsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'step_description' => [
                'type' => 'VARCHAR',
                'contrain' => '255',
                'null' => false,
            ],
            'step_number' => [
                'type' => 'INTEGER',
                'unsigned' => true,
            ],
            'id_recipes' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['step_number', 'id_recipes']); //Couple unique entre stage_number et id_recipes
        $this->forge->createTable('recipe_steps');
    }

    public function down()
    {
        $this->forge->dropTable('recipe_steps');
    }
}
