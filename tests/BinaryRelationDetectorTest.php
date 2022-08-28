<?php

use Tests\TestCase;

final class BinaryRelationDetectorTest extends TestCase
{
    public function testDirectedBy()
    {
        $modelsPath = $this->modelsPath();
        $detector = new Mitie\BinaryRelationDetector("$modelsPath/binary_relations/rel_classifier_film.film.directed_by.svm");
        $this->assertEquals('film.film.directed_by', $detector->name());
        $doc = $this->model()->doc('The Shawshank Redemption was directed by Frank Darabont and starred Tim Robbins and Morgan Freeman');

        $relations = $detector->relations($doc);
        $this->assertCount(1, $relations);

        $relation = $relations[0];
        $this->assertEquals('Shawshank Redemption', $relation['first']);
        $this->assertEquals('Frank Darabont', $relation['second']);
    }

    public function testPlaceFounded()
    {
        $modelsPath = $this->modelsPath();
        $detector = new Mitie\BinaryRelationDetector("$modelsPath/binary_relations/rel_classifier_organization.organization.place_founded.svm");
        $this->assertEquals('organization.organization.place_founded', $detector->name());
        $doc = $this->model()->doc('Shopify was founded in Ottawa');

        $relations = $detector->relations($doc);
        $this->assertCount(1, $relations);

        $relation = $relations[0];
        $this->assertEquals('Shopify', $relation['first']);
        $this->assertEquals('Ottawa', $relation['second']);
    }

    public function testNonDocument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected Mitie\Document');

        $modelsPath = $this->modelsPath();
        $detector = new Mitie\BinaryRelationDetector("$modelsPath/binary_relations/rel_classifier_film.film.directed_by.svm");
        $detector->relations('Hi');
    }

    public function testMissingFile()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File does not exist');

        new Mitie\BinaryRelationDetector('missing.dat');
    }
}
