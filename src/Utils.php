<?php

namespace Mitie;

class Utils
{
    public static function arrayToPointer($tokens)
    {
        $ffi = FFI::instance();
        $tokensSize = count($tokens);
        $tokensPtr = $ffi->new('char*[' . ($tokensSize + 1) . ']');
        $refs = [];
        for ($i = 0; $i < $tokensSize; $i++) {
            $ptr = self::cstring($tokens[$i]);
            $tokensPtr[$i] = $ffi->cast('char*', $ptr);
            $refs[] = $ptr;
        }
        return [$tokensPtr, $refs];
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
        $ptr = FFI::instance()->new("char[$bytes]");
        \FFI::memcpy($ptr, $str, $bytes - 1);
        $ptr[$bytes - 1] = "\0";
        return $ptr;
    }
}
