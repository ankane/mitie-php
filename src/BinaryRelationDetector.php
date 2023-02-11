<?php

namespace Mitie;

class BinaryRelationDetector
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
            $this->pointer = $this->ffi->mitie_load_binary_relation_detector($path);
        } elseif (!is_null($pointer)) {
            $this->pointer = $pointer;
        } else {
            throw new \InvalidArgumentException('Must pass either a path or a pointer');
        }
    }

    public function __destruct()
    {
        FFI::mitie_free($this->pointer);
    }

    public function name()
    {
        return $this->ffi->mitie_binary_relation_detector_name_string($this->pointer);
    }

    public function relations($doc)
    {
        if (!($doc instanceof Document)) {
            throw new \InvalidArgumentException('Expected Mitie\Document');
        }

        $entities = $doc->entities();
        $combinations = [];
        for ($i = 0; $i < count($entities) - 1; $i++) {
            $combinations[] = [$entities[$i], $entities[$i + 1]];
            $combinations[] = [$entities[$i + 1], $entities[$i]];
        }

        $relations = [];
        foreach ($combinations as [$entity1, $entity2]) {
            $relation = $this->extractRelation($doc, $entity1, $entity2);
            if (!is_null($relation)) {
                $relations[] = $relation;
            }
        }
        return $relations;
    }

    public function saveToDisk($filename)
    {
        if ($this->ffi->mitie_save_binary_relation_detector($filename, $this->pointer) != 0) {
            throw new Exception('Unable to save detector');
        }
    }

    private function extractRelation($doc, $entity1, $entity2)
    {
        try {
            $relation = $this->ffi->mitie_extract_binary_relation(
                $doc->model->pointer,
                $doc->tokensPtr,
                $entity1['token_index'],
                $entity1['token_length'],
                $entity2['token_index'],
                $entity2['token_length']
            );

            $scorePtr = $this->ffi->new('double');
            $status = $this->ffi->mitie_classify_binary_relation($this->pointer, $relation, \FFI::addr($scorePtr));
            if ($status != 0) {
                throw new Exception("Bad status: $status");
            }

            $score = $scorePtr->cdata;
            if ($score > 0) {
                return [
                    'first' => $entity1['text'],
                    'second' => $entity2['text'],
                    'score' => $score
                ];
            }
        } finally {
            FFI::mitie_free($relation);
        }
    }
}
