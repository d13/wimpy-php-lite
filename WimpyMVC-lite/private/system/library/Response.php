<?php
class Response {
	private static $buffer = "";
    public static function getBuffer() {
        return self::$buffer;
    }
    public static function setBuffer($bufferTxt) {
        self::$buffer = $bufferTxt;
    }
    public static function appendBuffer($bufferTxt) {
        self::$buffer .= $bufferTxt;
    }
    public static function clearBuffer() {
        self::setBuffer("");
    }
}