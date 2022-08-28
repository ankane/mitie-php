<?php

namespace Mitie;

class NER
{
    public function __construct($path)
    {
        $this->ffi = FFI::instance();

        if (!file_exists($path)) {
            throw new \InvalidArgumentException('File does not exist');
        }

        $this->pointer = $this->ffi->mitie_load_named_entity_extractor($path);
    }

    public function __destruct()
    {
        $this->ffi->mitie_free($this->pointer);
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

    public function tokens($text)
    {
        return $this->doc($text)->tokens();
    }

    public function tokensWithOffset($text)
    {
        return $this->doc($text)->tokensWithOffset();
    }
}