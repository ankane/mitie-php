<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private static $model;

    protected function model()
    {
        self::$model ??= new \Mitie\NER($this->modelsPath() . '/ner_model.dat');
        return self::$model;
    }

    protected function text()
    {
        return 'Nat works at GitHub in San Francisco';
    }

    private function modelsPath()
    {
        return getenv('MITIE_MODELS_PATH');
    }
}
