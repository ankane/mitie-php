<?php

namespace Mitie;

class NERTrainer
{
    // TODO make private in 0.2.0
    public $ffi;
    public $pointer;

    public function __construct($filename)
    {
        $this->ffi = FFI::instance();

        if (!file_exists($filename)) {
            throw new \InvalidArgumentException('File does not exist');
        }

        $this->pointer = $this->ffi->mitie_create_ner_trainer($filename);
    }

    public function __destruct()
    {
        FFI::mitie_free($this->pointer);
    }

    public function add($instance)
    {
        $this->ffi->mitie_add_ner_training_instance($this->pointer, $instance->pointer);
    }

    public function beta()
    {
        return $this->ffi->mitie_ner_trainer_get_beta($this->pointer);
    }

    public function setBeta($value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('beta must be greater than or equal to zero');
        }

        $this->ffi->mitie_ner_trainer_set_beta($this->pointer, $value);
    }

    public function numThreads()
    {
        return $this->ffi->mitie_ner_trainer_get_num_threads($this->pointer);
    }

    public function setNumThreads($value)
    {
        return $this->ffi->mitie_ner_trainer_set_num_threads($this->pointer, $value);
    }

    public function size()
    {
        return $this->ffi->mitie_ner_trainer_size($this->pointer);
    }

    public function train()
    {
        if ($this->size() == 0) {
            throw new Exception("You can't call train() on an empty trainer");
        }

        $extractor = $this->ffi->mitie_train_named_entity_extractor($this->pointer);

        if (is_null($extractor)) {
            throw new Exception('Unable to create named entity extractor. Probably ran out of RAM.');
        }

        return new NER(pointer: $extractor);
    }
}
