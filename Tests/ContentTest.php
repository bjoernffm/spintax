<?php

declare(strict_types=1);

use bjoernffm\Spintax\Content;
use PHPUnit\Framework\TestCase;

final class ContentTest extends TestCase
{
    public function testConstructor(): void
    {
        $source = 'test';
        $node = new Content($source);

        $this->assertEquals($source, $node->__toString(), 'Content::__construct() should set initial node content.');
    }

    public function testSetContent(): void
    {
        $source = 'test';
        $node = new Content();
        $node->setContent($source);

        $this->assertEquals($source, $node->__toString(), 'Content::setContent() should set node content.');
    }

    public function testAddChild(): void
    {
        $source = 'test';
        $node = new Content();
        $node->addChild(new Content($source));

        $this->assertEquals('{'.$source.'}', $node->__toString(), 'Content::addChild() should set node child.');
    }

    public function testSetNext(): void
    {
        $source = 'test';
        $node = new Content();
        $node->setNext(new Content($source));

        $this->assertEquals($source, $node->__toString(), 'Content::setNext() should set node sibling.');
    }

    public function testGenerateRandom(): void
    {
        $root = $this->buildTree();

        $path = [];
        $this->assertContains(
            $root->generate($path),
            ['I love PHP.', 'I love Java.', 'I love C.', 'I love C++.', 'I love JavaScript.', 'I love Python.', 'I hate Ruby.'],
            'Content::generate() should generate random available string.'
        );

        $this->assertNotEmpty($path, 'Content::generate() should fill passed argument with evaluated path.');
    }

    public function testGenerateDefined(): void
    {
        $root = $this->buildTree();

        $path = [0, 0];
        $this->assertEquals('I love PHP.', $root->generate($path), 'Content::generate() should generate particular variant if path is defined.');
    }

    public function testGeneratePartial(): void
    {
        $root = $this->buildTree();

        $path = [0];
        $this->assertContains(
            $root->generate($path),
            ['I love PHP.', 'I love Java.', 'I love C.', 'I love C++.', 'I love JavaScript.', 'I love Python.'],
            'Content::generate() should generate only strings available in pre-defined prefix path.'
        );

        $this->assertCount(2, $path, 'Content::generate() should fill the path with missing elements.');
    }

    public function testDump(): void
    {
        $root = $this->buildTree();

        $this->assertEquals('I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.', $root->dump(), 'Content::dump() should generate spintax string out of tree structure.');
    }

    public function testGetPaths(): void
    {
        $root = $this->buildTree();

        $this->assertEquals(
            [
                [0, 0],
                [0, 1],
                [0, 2],
                [0, 3],
                [0, 4],
                [0, 5],
                [1],
            ],
            $root->getPaths(),
            'Content::getPaths() should return list of all possible paths.'
        );
    }

    public function testExplicitCount(): void
    {
        $node = new Content();
        $node->addChild(new Content());
        $node->addChild(new Content());
        $node->setNext(new Content());

        $this->assertEquals(2, $node->count(), 'Content::count() should calculate number of possible permutations.');
    }

    public function testImplicitCount(): void
    {
        $node = new Content();
        $node->addChild(new Content());
        $node->addChild(new Content());
        $node->setNext(new Content());

        $this->assertCount(2, $node, 'Content::count() should handle internal PHP counting.');
    }

    public function testToStringConversion(): void
    {
        $source = 'test';
        $node = new Content($source);

        $this->assertEquals($source, $node->__toString(), 'Content::__toString() should dump spintax content.');
    }

    public function testToStringCasting(): void
    {
        $source = 'test';
        $node = new Content($source);

        $this->assertEquals($source, (string) $node, 'Content::__toString() should handle conversion to string.');
    }

    protected function buildTree(): Content
    {
        $root = new Content('I ');
        $node = new Content('love ');
        $node->addChild(new Content('PHP'));
        $node->addChild(new Content('Java'));
        $node->addChild(new Content('C'));
        $node->addChild(new Content('C++'));
        $node->addChild(new Content('JavaScript'));
        $node->addChild(new Content('Python'));
        $root->addChild($node);
        $root->addChild(new Content('hate Ruby'));
        $root->setNext(new Content('.'));

        return $root;
    }
}
