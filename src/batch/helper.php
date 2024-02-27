<?php

if (!function_exists('fn_ansi_echo')):

    /**
     * @access public
     * @param string|array $message
     * @param string $style
     * @param boolean $newline
     * @param int $escape
     * @return void
     */
    function fn_ansi_echo($message, $style = '', $newline = TRUE, $escape = 1)
    {

        if (!defined('ENVIRONMENT') || ENVIRONMENT !== 'development') {
            return;
        }
        // message 값이 없는 경우 CRLF
        else if ($message === NULL) {
            print PHP_EOL;
            return;
        }
        // 화면 지우기
        else if ($message == 'clear' || $message == 'cls') {
            fn_system('clear');
            return;
        }
        // 한 줄 지우기
        else if ($message == 'line-clear') {
            $limit = intval($style);
            if (!$limit) $limit = 1;
            for ($i = 0; $i < $limit; $i++) fn_system('echo -ne "\033[1A\033[2K"');
            return;
        }
        // Left first clear
        else if ($message == 'lf-clear') {
            print PHP_EOL;
            fn_system('echo -ne "\033[1A\033[2K"');
            return;
        }
        // -- 변수 선언
        $ansi_color = [
            'black' => 0,
            'red' => 1,
            'green' => 2,
            'yellow' => 3,
            'blue' => 4,
            'magenta' => 5,
            'cyan' => 6,
            'white' => 7,
            'default' => 9
        ];

        $ansi = [
            'reset' => '00',
            'bold' => '01',
            'underline' => '04',
            'blink' => '05',
            'un-bold' => '22',
            'un-underline' => '23',
            'un-blink' => '27',
            'color' => 30,
            'background' => 40
        ];

        $rows = [];
        $is_ansi = FALSE;

        // --
        $args = func_get_args();

        // message 변수가 배열인 경우
        if (is_array($message) === TRUE) {

            $newline = TRUE;
            $escape = 0;

            foreach ($args as $i => $arg) {
                if ($arg === FALSE) {
                    $newline = FALSE;
                }
                else if (is_int($arg) === TRUE) {
                    $escape = $arg;
                }
                else if (is_array($arg) === TRUE) {
                    $rows[] = $arg;
                }
                else if (is_string($arg) === TRUE && empty($arg) !== TRUE) {
                    $rows[] = [$arg];
                }

            }
        }
        else {
            $rows[] = [$message, $style];
        }

        unset($args, $i, $row);
        // --

        $message = '';
        foreach ($rows as $row) {
            list($text, $style) = $row;

            $escapes = [];
            $rules = explode(';', strtolower($style));

            //-- Ansi style
            foreach ($rules as $rule) {
                list($name, $code) = explode(':', trim($rule));

                if (isset($ansi[$name]) !== TRUE) {
                    continue;
                }
                // text / background color
                else if ($name == 'color' || $name == 'background') {
                    $value = $ansi[$name];

                    // light color
                    if (strpos($code, 'light-') !== FALSE) {
                        $value += 60;
                        $code = str_replace('light-', '', $code);
                    }

                    $escapes[] = $value + $ansi_color[$code];
                    unset($value);
                }
                else if ($code == 'normal') {
                    $escapes[] = $ansi['un-' . $name];
                }
                else {
                    $escapes[] = $ansi[$name];
                }
                $is_ansi = TRUE;
            }

            $escape !== -1 && ($text = escapeshellarg($text));
            count($escapes) > 0 && ( $text = '\\033[' . implode(';', $escapes) . 'm' . trim($text, '\'') . '\\033[0m');
            $message .= $text;
        }

        $option = '';
        $newline !== TRUE && ($option .= ' -n');

        if ($is_ansi === TRUE) {
            $option .= ' -e';
            $message = "\"" . $message . "\"";
        }

        $cmd = 'echo ';
        empty($option) !== TRUE && ($cmd .= $option . ' ');
        $cmd .= $message;
        fn_system($cmd);
    }
endif;

if (!function_exists('fn_tp')):
    /**
     * 경과 시간 계산(인식 가능하도록 시:분:초단위로 표시
     * @param $start_mtime microtime(TRUE)
     * @return string 00:00:00.000
     */
    function fn_tp($start_mtime)
    {
        $time = '';
        list($sec, $msec) = explode('.', sprintf('%0.3f', microtime(TRUE) - $start_mtime));

        if ($sec >= 3600) {// 1시간 이상
            $time .= sprintf('%02d', floor($sec / 3600)) . ':';
            $sec = $sec % 3600;
        }

        if ($sec >= 60) { // 1분 이상
            $time .= sprintf('%02d', floor($sec / 60)) . ':';
            $sec = $sec % 60;
        }
        else {
            $time .= '00:';
        }
        $time .= sprintf('%02d', $sec) . '.' . $msec;
        return $time;
    }
endif;

if (!function_exists('fn_ansi_progress')):

    /**
     * @param $subject
     * @param $index
     * @param $count
     * @param $start_mtime
     * @param int $clear_line
     * @param string|array|null $description
     */
    function fn_ansi_progress($subject, $index, $count, $start_mtime, $clear_line = NULL, $description = NULL)
    {
        if (!defined('ENVIRONMENT') || ENVIRONMENT != 'development') return;
        if ($index <= 0) $index = 1;

        $percent = ($index / $count) * 100;
        $percent = $percent >= 100 ? '100.0' : sprintf('%05.2f', $percent);

        $pad = strlen(number_format($count));


        fn_system('echo -ne "\033[2K"');
        $args = [
            [$subject . ': ', 'color:white;'],
            [$percent . '%', 'color:light-cyan;bold']
        ];
        $args[] = [' (elapsed: ', 'color:white;'];
        $args[] = [str_pad(number_format($index), $pad, ' ', STR_PAD_LEFT), 'color:light-cyan;bold'];
        $args[] = ['/' . number_format($count), 'color:white;bold'];
        $args[] = [', ' . fn_tp($start_mtime) . ')', 'color:white;'];

        if (empty($description) === FALSE) {
            $args = array_merge($args, is_array($description) === FALSE ? [[$description, 'color:white;bold']] : (is_array(reset($description)) === FALSE ? [$description] : $description));
        }

        call_user_func_array('fn_ansi_echo', $args);

        if ($clear_line > 0 && $index % $clear_line === 0 && $index >= $clear_line) {
            for ($i = 0; $i < $clear_line; $i++) {
                fn_system('echo -ne "\033[1A\033[2K"');
            }
        }
        else {
            fn_system('echo -e "\033[1A"');
        }

    }
endif;