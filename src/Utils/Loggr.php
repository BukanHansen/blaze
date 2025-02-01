<?php

namespace Blaze\Utils;

use function PHPSTORM_META\type;

class Loggr
{
    protected static function showArray(array $data, $start = null, $space = 4)
    {
        if ($start) {
            for ($i = 0; $i < $space; $i++) echo " ";
            echo ("\"$start\" => [\n");
        }
        else echo ("  [\n");
        foreach ($data as $key => $value) {
            $type = gettype($value);
            if (is_array($value)) self::showArray($value, $key, $space + 2);
            else {
                for ($i = 0; $i < $space; $i++) echo " ";

                if ($type == "string") echo ("\"$key\" => \"$value\" [{$type}], \n");
                else echo ("\"$key\" => $value [{$type}], \n");
            }
        }

        if ($start) {
            for ($i = 0; $i < $space; $i++) echo " ";
            echo ("],\n");
        } else echo ("  ]\n");
    }

    static function info($message, $prefix = "INFO")
    {
        $now = date("h:i:s");
        if (is_array($message)) self::showArray($message);
        else echo "[$now] [$prefix] $message\n";
    }

    static function warning($message, $prefix = "WARNING")
    {
        $now = date("h:i:s");
        if (is_array($message)) self::showArray($message);
        else echo "[$now] [$prefix] $message\n";
    }

    static function error($message, $prefix = "ERROR")
    {
        $now = date("h:i:s");
        echo "============== START OF ERROR ==============\n";
        if (is_array($message)) self::showArray($message);
        else echo "[$now] [$prefix] $message\n";
        echo "==============  END OF ERROR  ==============\n";
    }
}
