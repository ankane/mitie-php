<?php

use Tests\TestCase;

final class NERTrainingInstanceTest extends TestCase
{
    public function testAddEntityRaisesOnInvalidInput1()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $tokens = ['I', 'raise', 'errors', '.'];
        $instance = new Mitie\NERTrainingInstance($tokens);
        $instance->addEntity(2, 2, 'noun');
        $instance->addEntity(1, 0, 'nope');
    }

    public function testAddEntityRaisesOnInvalidInput2()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $tokens = ['I', 'raise', 'errors', '.'];
        $instance = new Mitie\NERTrainingInstance($tokens);
        $instance->addEntity(2, 2, 'noun');
        $instance->addEntity(1, 8, 'nope');
    }

    public function testAddEntityRaisesOnInvalidInput3()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $tokens = ['I', 'raise', 'errors', '.'];
        $instance = new Mitie\NERTrainingInstance($tokens);
        $instance->addEntity(2, 2, 'noun');
        $instance->addEntity(-1, 1, 'nope');
    }

    public function testAddEntityRaisesOnInvalidInput4()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Range overlaps existing entity');

        $tokens = ['I', 'raise', 'errors', '.'];
        $instance = new Mitie\NERTrainingInstance($tokens);
        $instance->addEntity(2, 2, 'noun');
        $instance->addEntity(2, 2, 'nope');
    }

    public function testNumEntities()
    {
        $tokens = ['You', 'can', 'do', 'machine', 'learning', 'in', 'PHP', '!'];
        $instance = new Mitie\NERTrainingInstance($tokens);

        $this->assertEquals(0, $instance->numEntities());

        $instance->addEntity(3, 4, 'topic');
        $instance->addEntity(6, 6, 'language');

        $this->assertEquals(2, $instance->numEntities());
    }

    public function testNumTokens()
    {
        $tokens = ['I', 'have', 'five', 'tokens', '.'];
        $instance = new Mitie\NERTrainingInstance($tokens);
        $this->assertEquals(5, $instance->numTokens());
    }

    public function testOverlapsAnyEntity()
    {
        $tokens = ['You', 'can', 'do', 'machine', 'learning', 'in', 'PHP', '!'];
        $instance = new Mitie\NERTrainingInstance($tokens);
        $instance->addEntity(3, 4, 'topic');
        $instance->addEntity(6, 6, 'language');

        $this->assertFalse($instance->overlapsAnyEntity(1, 2));
        $this->assertTrue($instance->overlapsAnyEntity(2, 3));
        $this->assertFalse($instance->overlapsAnyEntity(5, 5));
    }

    public function testOverlapsAnyEntityInvalidRange1()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $tokens = ['I', 'raise', 'errors', '.'];
        $instance = new Mitie\NERTrainingInstance($tokens);
        $instance->addEntity(2, 2, 'noun');
        $instance->overlapsAnyEntity(1, 0);
    }

    public function testOverlapsAnyEntityInvalidRange2()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $tokens = ['I', 'raise', 'errors', '.'];
        $instance = new Mitie\NERTrainingInstance($tokens);
        $instance->addEntity(2, 2, 'noun');
        $instance->overlapsAnyEntity(9, 12);
    }
}
