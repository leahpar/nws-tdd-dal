<?php

/**
 * Petite classe pour un peu de mise en forme des messages de tests
 */
class Test
{
    public static function assert(string $test, bool $cond)
    {
        if ($cond) {
            self::success("[OK] " . $test);
        }
        else {
            self::error("[KO] " . $test);
            die();
        }
    }

    public static function success($msg)
    {
        $green = "\033[0;32m";
        $default = "\033[0m";
        echo $green . $msg . $default . PHP_EOL;
    }

    public static function error($msg)
    {
        $green = "\033[0;31m";
        $default = "\033[0m";
        echo $green . $msg . $default . PHP_EOL;
    }

}
