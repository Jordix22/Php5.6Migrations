<?php

class Logger
{
    const BACKGROUND_RED = "41";
    const BACKGROUND_GREEN = "42";
    const BACKGROUND_LIGHT_GREY = "47";
    const TEXT_BLACK = "0;30";

    public function successMessage($message)
    {
        echo "\e[" . self::TEXT_BLACK . ";" . self::BACKGROUND_GREEN ."m$message\e[0m\n";
    }

    public function errorMessage($message)
    {
        echo "\e[" . self::TEXT_BLACK . ";" . self::BACKGROUND_RED ."m$message\e[0m\n";
    }

    public function infoMessage($message)
    {
        echo "\e[" . self::TEXT_BLACK . ";" . self::BACKGROUND_LIGHT_GREY ."m$message\e[0m\n";
    }
}

