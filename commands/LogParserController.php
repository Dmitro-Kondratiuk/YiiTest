<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\NginxLog;
use yii\console\ExitCode;

class LogParserController extends Controller
{
    public $logDir          = '/var/www/html/logs/access';
    public $positionFileDir = '/var/www/html/logs/positions';

    public function actionParseLogs() {
        if (!is_dir($this->positionFileDir)) {
            mkdir($this->positionFileDir, 0755, true);
        }

        $today    = date('Y-m-d');
        $logFiles = array_filter(glob($this->logDir . '*.log'), function ($file) use ($today) {
            return date('Y-m-d', filemtime($file)) === $today;
        });

        foreach ($logFiles as $logFile) {
            $this->parseLogFile($logFile);
        }

        return ExitCode::OK;
    }

    protected function parseLogFile($logFile) {
        $positionFile = $this->positionFileDir . '/' . basename($logFile) . '.pos';
        $position     = file_exists($positionFile) ? (int)file_get_contents($positionFile) : 0;

        $handle = fopen($logFile, 'r');
        if ($handle) {
            fseek($handle, $position);

            $batchSize = 1000;
            $logs      = [];

            while (($line = fgets($handle)) !== false) {
                $log = $this->parseLogLine($line);
                if ($log) {
                    $logs[] = $log;
                }

                if (count($logs) >= $batchSize) {
                    $this->saveLogs($logs);
                    $logs = [];
                }

                $position = ftell($handle);
            }
            fclose($handle);

            if (count($logs) > 0) {
                $this->saveLogs($logs);
            }

            file_put_contents($positionFile, $position);
        }
        else {
            Yii::error("Не удалось открыть файл: $logFile");
        }
    }

    protected function saveLogs($logs) {
        $existingLogs = NginxLog::find()
            ->where([
                'in',
                'CONCAT(ip_address, timestamp, request_method)',
                array_map(function ($log) {
                    return $log->ip_address . $log->timestamp . $log->request_method;
                }, $logs),
            ])
            ->all();

        $existingKeys = [];
        foreach ($existingLogs as $existingLog) {
            $existingKeys[$existingLog->ip_address . $existingLog->timestamp . $existingLog->request_method] = true;
        }

        foreach ($logs as $log) {
            $key = $log->ip_address . $log->timestamp . $log->request_method;
            if (!isset($existingKeys[$key])) {
                $log->save();
                $existingKeys[$key] = true;
            }
        }
    }

    protected function parseLogLine($line) {
        $pattern = '/^(?P<ip>[\d\.]+) - - \[(?P<datetime>[^\]]+)\] "(?P<method>[A-Z]+) (?P<url>[^ ]+) HTTP\/[0-9."]+" (?P<status>[0-9]+) (?P<bytes>\d+) "(?P<referer>[^"]*)" "(?P<user_agent>[^"]*)"/';

        if (preg_match($pattern, $line, $matches)) {
            $log                 = new NginxLog();
            $log->ip_address     = $matches['ip'];
            $log->timestamp      = date('Y-m-d H:i:s', strtotime($matches['datetime']));
            $log->request_method = $matches['method'];
            $log->request_url    = $matches['url'];
            $log->response_code  = $matches['status'];
            $log->response_size  = $matches['bytes'];
            $log->referer        = $matches['referer'];
            $log->user_agent     = $matches['user_agent'];

            return $log;
        }
        else {
            Yii::error("Ошибка парсинга строки: $line");

            return null;
        }
    }
}
