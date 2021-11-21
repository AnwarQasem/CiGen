<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Import extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'      => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'json'    => [
                'type'       => 'TEXT',
                'null'       => FALSE
            ],
            'data' => [
                'type'       => 'TEXT',
                'null'       => FALSE
            ],
            'created_at datetime default current_timestamp',
            'updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],

        ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->createTable('import_map');
    }

    public function down()
    {
        $this->forge->dropTable('import_map');
    }
}
