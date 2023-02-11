<?php

namespace Mitie;

class NERTrainingInstance
{
    // TODO make private in 0.2.0
    public $ffi;
    public $pointer;

    public function __construct($tokens)
    {
        $this->ffi = FFI::instance();

        $tokensPointer = Utils::arrayToPointer($tokens);

        $this->pointer = $this->ffi->mitie_create_ner_training_instance($tokensPointer);
        if (is_null($this->pointer)) {
            throw new Exception('Unable to create training instance. Probably ran out of RAM.');
        }
    }

    public function __destruct()
    {
        FFI::mitie_free($this->pointer);
    }

    public function addEntity($start, $end, $label)
    {
        Utils::checkRange($start, $end, $this->numTokens());

        if ($this->overlapsAnyEntity($start, $end)) {
            throw new \InvalidArgumentException('Range overlaps existing entity');
        }

        if ($this->ffi->mitie_add_ner_training_entity($this->pointer, $start, $end - $start + 1, $label) != 0) {
            throw new Exception('Unable to add entity to training instance. Probably ran out of RAM.');
        }
    }

    public function numEntities()
    {
        return $this->ffi->mitie_ner_training_instance_num_entities($this->pointer);
    }

    public function numTokens()
    {
        return $this->ffi->mitie_ner_training_instance_num_tokens($this->pointer);
    }

    public function overlapsAnyEntity($start, $end)
    {
        Utils::checkRange($start, $end, $this->numTokens());

        return $this->ffi->mitie_overlaps_any_entity($this->pointer, $start, $end - $start + 1) == 1;
    }
}
