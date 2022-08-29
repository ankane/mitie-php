<?php

use Tests\TestCase;

final class DocumentTest extends TestCase
{
    public function testEntities()
    {
        $expected = [
            ['text' => 'Nat', 'tag' => 'PERSON', 'score' => 0.31123712126883823, 'offset' => 0, 'token_index' => 0, 'token_length' => 1],
            ['text' => 'GitHub', 'tag' => 'LOCATION', 'score' => 0.5660115198329334, 'offset' => 13, 'token_index' => 3, 'token_length' => 1],
            ['text' => 'San Francisco', 'tag' => 'LOCATION', 'score' => 1.3890524313885309, 'offset' => 23, 'token_index' => 5, 'token_length' => 2]
        ];
        $this->assertEquals($expected, $this->doc()->entities());
    }

    public function testEntitiesTokens()
    {
        $this->markTestSkipped('Not supported yet');

        $expected = [
            ['text' => ['Nat'], 'tag' => 'PERSON', 'score' => 0.31123712126883823, 'token_index' => 0, 'token_length' => 1],
            ['text' => ['GitHub'], 'tag' => 'LOCATION', 'score' => 0.5660115198329334, 'token_index' => 3, 'token_length' => 1],
            ['text' => ['San', 'Francisco'], 'tag' => 'LOCATION', 'score' => 1.3890524313885309, 'token_index' => 5, 'token_length' => 2]
        ];
        $this->assertEquals($expected, $this->tokenDoc()->entities());
    }

    public function testEntitiesLocation()
    {
        // would ideally return a single location
        $this->assertEquals(['San Francisco', 'California'], array_map(fn ($e) => $e['text'], $this->model()->doc('San Francisco, California')->entities()));
    }

    // offset is in bytes
    public function testEntitiesByteOrderMark()
    {
        $expected = [['text' => 'California', 'tag' => 'LOCATION', 'score' => 1.4244816233933328, 'offset' => 12, 'token_index' => 2, 'token_length' => 1]];
        $this->assertEquals($expected, $this->model()->doc("\xEF\xBB\xBFWorks in California")->entities());
    }

    public function testTokens()
    {
        $expected = ['Nat', 'works', 'at', 'GitHub', 'in', 'San', 'Francisco'];
        $this->assertEquals($expected, $this->doc()->tokens());
    }

    public function testTokensTokens()
    {
        $this->markTestSkipped('Not supported yet');

        $expected = ['Nat', 'works', 'at', 'GitHub', 'in', 'San', 'Francisco'];
        $this->assertEquals($expected, $this->tokenDoc()->tokens());
    }

    public function testTokensWithOffset()
    {
        $expected = [['Nat', 0], ['works', 4], ['at', 10], ['GitHub', 13], ['in', 20], ['San', 23], ['Francisco', 27]];
        $this->assertEquals($expected, $this->doc()->tokensWithOffset());
    }

    public function testTokensWithOffsetTokens()
    {
        $this->markTestSkipped('Not supported yet');

        $expected =[['Nat', null], ['works', null], ['at', null], ['GitHub', null], ['in', null], ['San', null], ['Francisco', null]];
        $this->assertEquals($expected, $this->tokenDoc()->tokensWithOffset());
    }

    public function testTokensUtf8()
    {
        $this->assertEquals(['“', 'hello', '”'], $this->model()->doc('“hello”')->tokens());
    }

    public function testTokensWithOffsetUtf8()
    {
        // https://github.com/mit-nlp/MITIE/issues/211
        $this->markTestSkipped('Possible bug with MITIE');

        $this->assertEquals([['“', 0], ['hello', 1], ['”', 6]], $this->model()->doc('“hello”')->tokensWithOffset());
    }

    private function doc()
    {
        return $this->model()->doc($this->text());
    }

    private function tokenDoc()
    {
        return $this->model()->doc($this->tokens());
    }

    private function tokens()
    {
        return ['Nat', 'works', 'at', 'GitHub', 'in', 'San', 'Francisco'];
    }
}
