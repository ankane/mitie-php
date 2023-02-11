<?php

namespace Mitie;

class Document
{
    // TODO make private in 0.2.0
    public $ffi;
    public $model;
    public $text;
    public $offsetsPtr;
    public $tokensPtr;

    public function __construct($model, $text)
    {
        $this->ffi = FFI::instance();

        $this->model = $model;
        $this->text = $text;

        $this->offsetsPtr = $this->ffi->new('unsigned long*');
        $this->tokensPtr = $this->ffi->mitie_tokenize_with_offsets($this->text, \FFI::addr($this->offsetsPtr));
    }

    public function __destruct()
    {
        FFI::mitie_free($this->offsetsPtr);
        FFI::mitie_free($this->tokensPtr);
    }

    public function tokens()
    {
        return array_map(fn ($v) => $v[0], $this->tokensWithOffset());
    }

    public function tokensWithOffset()
    {
        $i = 0;
        $tokens = [];
        while (true) {
            $token = $this->tokensPtr[$i];
            if (is_null($token)) {
                break;
            }
            $offset = $this->offsetsPtr[$i];
            $tokens[] = [\FFI::string($token), $offset];
            $i++;
        }
        return $tokens;
    }

    // TODO memoize
    public function entities()
    {
        try {
            $entities = [];
            $tokens = $this->tokensWithOffset();
            $detections = $this->ffi->mitie_extract_entities($this->model->pointer, $this->tokensPtr);
            $numDetections = $this->ffi->mitie_ner_get_num_detections($detections);
            for ($i = 0; $i < $numDetections; $i++) {
                $pos = $this->ffi->mitie_ner_get_detection_position($detections, $i);
                $len = $this->ffi->mitie_ner_get_detection_length($detections, $i);
                $tag = $this->ffi->mitie_ner_get_detection_tagstr($detections, $i);
                $score = $this->ffi->mitie_ner_get_detection_score($detections, $i);
                $tok = array_slice($tokens, $pos, $len);
                $offset = $tok[0][1];

                $entity = [];
                if (!is_null($offset)) {
                    $finish = end($tok)[1] + strlen(end($tok)[0]);
                    $entity['text'] = substr($this->text, $offset, $finish - $offset);
                } else {
                    $entity['text'] = array_map(fn ($v) => $v[0], $tok);
                }
                $entity['tag'] = $tag;
                $entity['score'] = $score;
                if (!is_null($offset)) {
                    $entity['offset'] = $offset;
                }
                $entity['token_index'] = $pos;
                $entity['token_length'] = $len;
                $entities[] = $entity;
            }

            return $entities;
        } finally {
            FFI::mitie_free($detections);
        }
    }
}
