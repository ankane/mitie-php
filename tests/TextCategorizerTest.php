<?php

use Tests\TestCase;

final class TextCategorizerTest extends TestCase
{
    public function testTokens()
    {
        $this->markTestSkipped('TODO');

        $trainer = new Mitie\TextCategorizerTrainer($this->featureExtractorPath());
        $trainer->add(['This', 'is', 'super', 'cool'], 'positive');
        $trainer->add(['I', 'am', 'not', 'a', 'fan'], 'negative');
        $model = $trainer->train();

        $path = tempnam(sys_get_temp_dir(), 'model');
        $model->saveToDisk($path);
        $this->assertFileExists($path);

        $model = new Mitie\TextCategorizer($path);
        $result = $model->categorize(['What', 'a', 'super', 'nice', 'day']);
        $this->assertEquals('positive', $result['tag']);
        $this->assertEqualsWithDelta(0.0684, $result['score'], 0.001);
    }

    public function testStrings()
    {
        $trainer = new Mitie\TextCategorizerTrainer($this->featureExtractorPath());
        $trainer->add('This is super cool', 'positive');
        $trainer->add('I am not a fan', 'negative');
        $model = $trainer->train();

        $path = tempnam(sys_get_temp_dir(), 'model');
        $model->saveToDisk($path);
        $this->assertFileExists($path);

        $model = new Mitie\TextCategorizer($path);
        $result = $model->categorize('What a super nice day');
        $this->assertEquals('positive', $result['tag']);
        $this->assertEqualsWithDelta(0.0684, $result['score'], 0.001);
    }

    public function testEmptyTrainer()
    {
        $this->expectException(Mitie\Exception::class);
        $this->expectExceptionMessage("You can't call train() on an empty trainer");

        $trainer = new Mitie\TextCategorizerTrainer($this->featureExtractorPath());
        $trainer->train();
    }
}
