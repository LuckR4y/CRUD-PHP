<?php

abstract class Controller
{
    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');


        $out = json_encode($data, JSON_UNESCAPED_UNICODE);

        if ($out === false) {
            $data = $this->utf8ize($data);
            $out = json_encode($data, JSON_UNESCAPED_UNICODE);

            if ($out === false) {
                http_response_code(500);
                $out = json_encode([
                    'error' => 'json_encode failed',
                    'msg' => json_last_error_msg()
                ], JSON_UNESCAPED_UNICODE);
            }
        }

        echo $out;
        exit;
    }

    protected function input(): array
    {
        $payload = $_POST;

        if (empty($payload)) {
            $raw = file_get_contents('php://input');
            if ($raw !== false && $raw !== '') {
                $json = json_decode($raw, true);
                if (is_array($json)) {
                    $payload = $json;
                }
            }
        }

        return $payload ?? [];
    }

    private function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $k => $v) {
                $mixed[$k] = $this->utf8ize($v);
            }
            return $mixed;
        }
        if (is_string($mixed)) {
            return mb_detect_encoding($mixed, 'UTF-8', true)
                ? $mixed
                : mb_convert_encoding($mixed, 'UTF-8', 'ISO-8859-1, Windows-1252, UTF-8');
        }
        return $mixed;
    }
}
