<?php

use Tests\TestCase;

final class NERTrainerTest extends TestCase
{
    public function testBetaAccessors()
    {
        $trainer = new Mitie\NERTrainer($this->featureExtractorPath());
        $trainer->setBeta(2.0);
        $this->assertEquals(2.0, $trainer->beta());
    }

    public function testBetaWriterRaisesOnInvalidInput()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('beta must be greater than or equal to zero');

        $trainer = new Mitie\NERTrainer($this->featureExtractorPath());
        $trainer->setBeta(-0.5);
    }

    public function testNumThreadsAccessors()
    {
        $trainer = new Mitie\NERTrainer($this->featureExtractorPath());
        $trainer->setNumThreads(2);
        $this->assertEquals(2, $trainer->numThreads());
    }

    public function testTrain()
    {
        $tokens = ['You', 'can', 'do', 'machine', 'learning', 'in', 'PHP', '!'];
        $instance = new Mitie\NERTrainingInstance($tokens);
        $instance->addEntity(3, 4, 'topic');
        $instance->addEntity(6, 6, 'language');

        $trainer = new Mitie\NERTrainer($this->featureExtractorPath());
        $trainer->add($instance);
        $trainer->setNumThreads(2);
        $model = $trainer->train();

        $entity = $model->doc('Code in PHP')->entities()[0];
        $this->assertEquals('PHP', $entity['text']);
        $this->assertEquals('language', $entity['tag']);
    }

    public function testEmptyTrainer()
    {
        $this->expectException(Mitie\Exception::class);
        $this->expectExceptionMessage("You can't call train() on an empty trainer");

        $trainer = new Mitie\NERTrainer($this->featureExtractorPath());
        $trainer->train();
    }

    public function testMissingFile()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File does not exist');

        new Mitie\NERTrainer('missing.dat');
    }
}
