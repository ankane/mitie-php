<?php

use Tests\TestCase;

final class NERTest extends TestCase
{
    public function testEntities()
    {
        $expected = [
            ['text' => 'Nat', 'tag' => 'PERSON', 'score' => 0.31123712126883823, 'offset' => 0, 'token_index' => 0, 'token_length' => 1],
            ['text' => 'GitHub', 'tag' => 'LOCATION', 'score' => 0.5660115198329334, 'offset' => 13, 'token_index' => 3, 'token_length' => 1],
            ['text' => 'San Francisco', 'tag' => 'LOCATION', 'score' => 1.3890524313885309, 'offset' => 23, 'token_index' => 5, 'token_length' => 2]
        ];
        $this->assertEquals($expected, $this->model()->entities($this->text()));
    }

    public function testTokens()
    {
        $expected = ['Nat', 'works', 'at', 'GitHub', 'in', 'San', 'Francisco'];
        $this->assertEquals($expected, $this->model()->tokens($this->text()));
    }

    public function testTokensUtf8()
    {
        $this->assertEquals(['“', 'hello', '”'], $this->model()->tokens('“hello”'));
    }

    public function testTokensWithOffset()
    {
        $expected = [['Nat', 0], ['works', 4], ['at', 10], ['GitHub', 13], ['in', 20], ['San', 23], ['Francisco', 27]];
        $this->assertEquals($expected, $this->model()->tokensWithOffset($this->text()));
    }

    public function testTags()
    {
        $this->assertEquals(['PERSON', 'LOCATION', 'ORGANIZATION', 'MISC'], $this->model()->tags());
    }

    public function testMissingFile()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File does not exist');

        new Mitie\NER('missing.dat');
    }

    public function testSaveToDisk()
    {
        $path = tempnam(sys_get_temp_dir(), 'model');
        $this->model()->saveToDisk($path);
        $this->assertFileExists($path);
        unlink($path);
    }

    public function testSaveToDiskError()
    {
        $this->expectException(Mitie\Exception::class);
        $this->expectExceptionMessage('Unable to save model');

        $this->model()->saveToDisk('missing/ner_model.dat');
    }
}
