<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IngredientsTable extends Migration
{
    /**
     * Création de la table ingredients
     */
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('ingredients');
    }

    /**
     * DROP Table ingredients
     */
    public function down()
    {
        $this->forge->dropTable('ingredients');
    }
}
