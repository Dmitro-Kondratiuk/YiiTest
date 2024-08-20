<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "nginx_logs".
 *
 * @property int         $id
 * @property string|null $ip_address
 * @property string|null $timestamp
 * @property string|null $request_method
 * @property string|null $request_url
 * @property int|null    $response_code
 * @property int|null    $response_size
 * @property string|null $referer
 * @property string|null $user_agent
 */
class NginxLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'nginx_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['timestamp'], 'safe'],
            [['response_code', 'response_size'], 'integer'],
            [['ip_address'], 'string', 'max' => 45],
            [['request_method'], 'string', 'max' => 10],
            [['request_url', 'referer', 'user_agent'], 'string', 'max' => 2083],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id'              => 'ID',
            'ip_address'      => 'Ip Address',
            'timestamp'       => 'Timestamp',
            'request_method'  => 'Request Method',
            'request_url'     => 'Request Url',
            'response_code'   => 'Response Code',
            'response_size'   => 'Response Size',
            'referer'         => 'Referer',
            'user_agent'      => 'User Agent',
        ];
    }
}
