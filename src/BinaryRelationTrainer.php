<?php

namespace Mitie;

class BinaryRelationTrainer
{
    // TODO make private in 0.2.0
    public $ffi;
    public $pointer;

    public function __construct($ner, $name = '')
    {
        $this->ffi = FFI::instance();

        $this->pointer = $this->ffi->mitie_create_binary_relation_trainer($name, $ner->pointer);
    }

    public function __destruct()
    {
        FFI::mitie_free($this->pointer);
    }

    public function addPositiveBinaryRelation($tokens, $range1, $range2)
    {
        $this->checkAdd($tokens, $range1, $range2);

        $tokensPointer = Utils::arrayToPointer($tokens);
        $status = $this->ffi->mitie_add_positive_binary_relation($this->pointer, $tokensPointer, $range1[0], $range1[1] - $range1[0] + 1, $range2[0], $range2[1] - $range2[0] + 1);
        if ($status != 0) {
            throw new Exception('Unable to add binary relation');
        }
    }

    public function addNegativeBinaryRelation($tokens, $range1, $range2)
    {
        $this->checkAdd($tokens, $range1, $range2);

        $tokensPointer = Utils::arrayToPointer($tokens);
        $status = $this->ffi->mitie_add_negative_binary_relation($this->pointer, $tokensPointer, $range1[0], $range1[1] - $range1[0] + 1, $range2[0], $range2[1] - $range2[0] + 1);
        if ($status != 0) {
            throw new Exception('Unable to add binary relation');
        }
    }

    public function beta()
    {
        return $this->ffi->mitie_binary_relation_trainer_get_beta($this->pointer);
    }

    public function setBeta($value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('beta must be greater than or equal to zero');
        }

        $this->ffi->mitie_binary_relation_trainer_set_beta($this->pointer, $value);
    }

    public function numThreads()
    {
        return $this->ffi->mitie_binary_relation_trainer_get_num_threads($this->pointer);
    }

    public function setNumThreads($value)
    {
        return $this->ffi->mitie_binary_relation_trainer_set_num_threads($this->pointer, $value);
    }

    public function numPositiveExamples()
    {
        return $this->ffi->mitie_binary_relation_trainer_num_positive_examples($this->pointer);
    }

    public function numNegativeExamples()
    {
        return $this->ffi->mitie_binary_relation_trainer_num_negative_examples($this->pointer);
    }

    public function train()
    {
        if ($this->numPositiveExamples() + $this->numNegativeExamples() == 0) {
            throw new Exception("You can't call train() on an empty trainer");
        }

        $detector = $this->ffi->mitie_train_binary_relation_detector($this->pointer);

        if (is_null($detector)) {
            throw new Exception('Unable to create binary relation detector. Probably ran out of RAM.');
        }

        return new BinaryRelationDetector(pointer: $detector);
    }

    private function checkAdd($tokens, $range1, $range2)
    {
        Utils::checkRange($range1[0], $range1[1], count($tokens));
        Utils::checkRange($range2[0], $range2[1], count($tokens));

        if ($this->entitiesOverlap($range1, $range2)) {
            throw new \InvalidArgumentException('Entities overlap');
        }
    }

    private function entitiesOverlap($range1, $range2)
    {
        return $this->ffi->mitie_entities_overlap($range1[0], $range1[1] - $range1[0] + 1, $range2[0], $range2[1] - $range2[0] + 1) == 1;
    }
}
