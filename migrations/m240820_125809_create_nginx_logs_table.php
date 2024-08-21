<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%nginx_logs}}`.
 */
class m240820_125809_create_nginx_logs_table extends Migration
{
    public function up() {
        $this->createTable('nginx_logs', [
            'id'             => $this->primaryKey(),
            'ip_address'     => $this->string(45),
            'timestamp'      => $this->timestamp(),
            'request_method' => $this->string(10),
            'request_url'    => $this->string(2083),
            'response_code'  => $this->integer(),
            'response_size'  => $this->integer(),
            'referer'        => $this->text()->null(),
            'user_agent'     => $this->text()->null(),
        ]);

    }

    public function down() {
        $this->dropTable('nginx_logs');
    }
}

