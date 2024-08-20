<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\NginxLog;
use yii\console\ExitCode;

class LogParserController extends Controller
{
    public $logDir = '/var/www/html/logs/access';
    public $positionFileDir = '/var/www/html/logs/positions';


    public function actionParseLogs() {

        if (!is_dir($this->positionFileDir)) {
            mkdir($this->positionFileDir, 0755, true);
        }

        $logFiles = glob($this->logDir . '*.log');
        foreach ($logFiles as $logFile) {
            $this->parseLogFile($logFile);
        }
        return ExitCode::OK;
    }

    protected function parseLogFile($logFile) {
        $positionFile = $this->positionFileDir . '/' . basename($logFile) . '.pos';
        $position = file_exists($positionFile) ? (int)file_get_contents($positionFile) : 0;

        $handle = fopen($logFile, 'r');
        if ($handle) {
            fseek($handle, $position);

            while (($line = fgets($handle)) !== false) {
                $this->parseLogLine($line);
                $position = ftell($handle);
                file_put_contents($positionFile, $position);
            }

            fclose($handle);

        } else {
            Yii::error("Не удалось открыть файл: $logFile");
        }
    }

    protected function parseLogLine($line)
    {
        $pattern = '/^(?P<ip>[\d\.]+) - - \[(?P<datetime>[^\]]+)\] "(?P<method>[A-Z]+) (?P<url>[^ ]+) HTTP\/[0-9."]+" (?P<status>[0-9]+) (?P<bytes>\d+) "(?P<referer>[^"]*)" "(?P<user_agent>[^"]*)"/';

        if (preg_match($pattern, $line, $matches)) {
            $log = new NginxLog();
            $log->ip_address = $matches['ip'];
            $log->timestamp = date('Y-m-d H:i:s', strtotime($matches['datetime']));
            $log->request_method = $matches['method'];
            $log->request_url = $matches['url'];
            $log->response_code = $matches['status'];
            $log->response_size = $matches['bytes'];
            $log->referer = $matches['referer'];
            $log->user_agent = $matches['user_agent'];
            $existingLog = NginxLog::find()
                ->where(['ip_address' => $log->ip_address, 'timestamp' => $log->timestamp, 'request_method' => $log->request_method])
                ->one();

            if (!$existingLog) {
                $log->save();
            }
        } else {
            Yii::error("Ошибка парсинга строки: $line");
        }
    }

    protected function saveLogData($logData) {
        $existingLog = NginxLog::findOne([
            'ip_address'  => $logData['ip_address'],
            'timestamp'   => $logData['timestamp'],
            'request_url' => $logData['request_url'],
        ]);

        if (!$existingLog) {
            $log             = new NginxLog();
            $log->attributes = $logData;
            $log->save();
        }
    }
}
