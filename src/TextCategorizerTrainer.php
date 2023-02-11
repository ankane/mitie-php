<?php

namespace Mitie;

class TextCategorizerTrainer
{
    // TODO make private in 0.2.0
    public $ffi;
    public $pointer;

    public function __construct($path)
    {
        $this->ffi = FFI::instance();

        if (!file_exists($path)) {
            throw new \InvalidArgumentException('File does not exist');
        }

        $this->pointer = $this->ffi->mitie_create_text_categorizer_trainer($path);
    }

    public function __destruct()
    {
        $this->ffi->mitie_free($this->pointer);
    }

    public function add($text, $label)
    {
        try {
            // TODO support tokens
            $tokensPtr = $this->ffi->mitie_tokenize($text);
            $this->ffi->mitie_add_text_categorizer_labeled_text($this->pointer, $tokensPtr, $label);
        } finally {
            FFI::mitie_free($tokensPtr);
        }
    }

    public function beta()
    {
        return $this->ffi->mitie_text_categorizer_trainer_get_beta($this->pointer);
    }

    public function setBeta($value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('beta must be greater than or equal to zero');
        }

        $this->ffi->mitie_text_categorizer_trainer_set_beta($this->pointer, $value);
    }

    public function numThreads()
    {
        return $this->ffi->mitie_text_categorizer_trainer_get_num_threads($this->pointer);
    }

    public function setNumThreads($value)
    {
        $this->ffi->mitie_text_categorizer_trainer_set_num_threads($this->pointer, $value);
    }

    public function size()
    {
        return $this->ffi->mitie_text_categorizer_trainer_size($this->pointer);
    }

    public function train()
    {
        if ($this->size() == 0) {
            throw new Exception("You can't call train() on an empty trainer");
        }

        $categorizer = $this->ffi->mitie_train_text_categorizer($this->pointer);

        if (is_null($categorizer)) {
            throw new Exception('Unable to create text categorizer. Probably ran out of RAM.');
        }

        return new TextCategorizer(pointer: $categorizer);
    }
}
