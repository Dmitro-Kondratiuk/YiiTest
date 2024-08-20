<?php

use yii\db\Migration;

/**
 * Class m240820_145028_create_admin_user
 */
class m240820_145028_create_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('users', [
            'username' => 'admin',
            'password_hash' => Yii::$app->security->generatePasswordHash('password2024'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('users', ['username' => 'admin']);
    }

}
