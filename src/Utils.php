<?php

namespace Mitie;

class Utils
{
    public static function arrayToPointer($tokens)
    {
        $tokensSize = count($tokens);
        $tokensPtr = FFI::instance()->new('char*[' . ($tokensSize + 1) . ']');
        for ($i = 0; $i < $tokensSize; $i++) {
            $tokensPtr[$i] = self::cstring($tokens[$i]);
        }
        return $tokensPtr;
    }

    public static function checkRange($start, $end, $numTokens)
    {
        if ($start > $end || $start < 0 || $end >= $numTokens) {
            throw new \InvalidArgumentException('Invalid range');
        }
    }

    private static function cstring($str)
    {
        $bytes = strlen($str) + 1;
        // TODO fix?
        $ptr = FFI::instance()->new("char[$bytes]", owned: false);
        \FFI::memcpy($ptr, $str, $bytes - 1);
        $ptr[$bytes - 1] = "\0";
        return $ptr;
    }
}
