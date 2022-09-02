<?php

use Tests\TestCase;

final class BinaryRelationTrainerTest extends TestCase
{
    public function testWorks()
    {
        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->addPositiveBinaryRelation($this->tokens(), [0, 0], [4, 4]);
        $trainer->addNegativeBinaryRelation($this->tokens(), [4, 4], [0, 0]);
        $this->assertEquals(1, $trainer->numPositiveExamples());
        $this->assertEquals(1, $trainer->numNegativeExamples());
        $detector = $trainer->train();
        $this->assertEquals('', $detector->name());

        $path = tempnam(sys_get_temp_dir(), 'detector');
        $detector->saveToDisk($path);
        $this->assertFileExists($path);

        $detector = new Mitie\BinaryRelationDetector($path);
        $doc = $this->model()->doc('Shopify was founded in Ottawa');

        $relations = $detector->relations($doc);
        $this->assertCount(1, $relations);

        $relation = $relations[0];
        $this->assertEquals('Shopify', $relation['first']);
        $this->assertEquals('Ottawa', $relation['second']);
    }

    public function testAddPositiveBinaryRelationInvalidRange1()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->addPositiveBinaryRelation($this->tokens(), [0, -1], [4, 4]);
    }

    public function testAddPositiveBinaryRelationInvalidRange2()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->addPositiveBinaryRelation($this->tokens(), [0, 0], [4, 3]);
    }

    public function testAddPositiveBinaryRelationInvalidRange3()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->addPositiveBinaryRelation($this->tokens(), [0, 0], [4, 5]);
    }

    public function testAddNegativeBinaryRelationInvalidRange1()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->addNegativeBinaryRelation($this->tokens(), [0, -1], [4, 4]);
    }

    public function testAddNegativeBinaryRelationInvalidRange2()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->addNegativeBinaryRelation($this->tokens(), [0, 0], [4, 3]);
    }

    public function testAddNegativeBinaryRelationInvalidRange3()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid range');

        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->addNegativeBinaryRelation($this->tokens(), [0, 0], [4, 5]);
    }

    public function testAddPositiveBinaryRelationEntitiesOverlap()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Entities overlap');

        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->addPositiveBinaryRelation($this->tokens(), [0, 1], [1, 2]);
    }

    public function testAddNegativeBinaryRelationEntitiesOverlap()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Entities overlap');

        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->addNegativeBinaryRelation($this->tokens(), [0, 1], [1, 2]);
    }

    public function testEmptyTrainer()
    {
        $this->expectException(Mitie\Exception::class);
        $this->expectExceptionMessage("You can't call train() on an empty trainer");

        $trainer = new Mitie\BinaryRelationTrainer($this->model());
        $trainer->train();
    }

    private function tokens()
    {
        return ['Shopify', 'was', 'founded', 'in', 'Ottawa'];
    }
}
