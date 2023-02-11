<?php

namespace Mitie;

class TextCategorizer
{
    // TODO make private in 0.2.0
    public $ffi;
    public $pointer;

    public function __construct($path = null, $pointer = null)
    {
        $this->ffi = FFI::instance();

        if (!is_null($path)) {
            if (!file_exists($path)) {
                throw new \InvalidArgumentException('File does not exist');
            }
            $this->pointer = $this->ffi->mitie_load_text_categorizer($path);
        } elseif (!is_null($pointer)) {
            $this->pointer = $pointer;
        } else {
            throw new \InvalidArgumentException('Must pass either a path or a pointer');
        }
    }

    public function __destruct()
    {
        $this->ffi->mitie_free($this->pointer);
    }

    public function categorize($text)
    {
        try {
            // TODO support tokens
            $tokensPtr = $this->ffi->mitie_tokenize($text);

            $textTag = $this->ffi->new('char*');
            $textScore = $this->ffi->new('double');

            if ($this->ffi->mitie_categorize_text($this->pointer, $tokensPtr, \FFI::addr($textTag), \FFI::addr($textScore)) != 0) {
                throw new Exception('Unable to categorize');
            }

            return [
                'tag' => \FFI::string($textTag),
                'score' => $textScore->cdata
            ];
        } finally {
            FFI::mitie_free($tokensPtr);
            FFI::mitie_free($textTag);
        }
    }

    public function saveToDisk($filename)
    {
        if ($this->ffi->mitie_save_text_categorizer($filename, $this->pointer) != 0) {
            throw new Exception('Unable to save model');
        }
    }
}
