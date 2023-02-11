<?php

namespace Mitie;

class NER
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
            $this->pointer = $this->ffi->mitie_load_named_entity_extractor($path);
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

    public function tags()
    {
        $tagsCount = $this->ffi->mitie_get_num_possible_ner_tags($this->pointer);
        $tags = [];
        for ($i = 0; $i < $tagsCount; $i++) {
            $tags[] = $this->ffi->mitie_get_named_entity_tagstr($this->pointer, $i);
        }
        return $tags;
    }

    public function doc($text)
    {
        return new Document($this, $text);
    }

    public function entities($text)
    {
        return $this->doc($text)->entities();
    }

    public function saveToDisk($filename)
    {
        if ($this->ffi->mitie_save_named_entity_extractor($filename, $this->pointer) != 0) {
            throw new Exception('Unable to save model');
        }
    }

    public function tokens($text)
    {
        return $this->doc($text)->tokens();
    }

    public function tokensWithOffset($text)
    {
        return $this->doc($text)->tokensWithOffset();
    }
}
