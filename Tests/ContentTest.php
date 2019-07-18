<?php

/**
 * This file is part of the ChillDev Spintax library.
 *
 * @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
 * @copyright 2014 © by Rafał Wrzeszcz - Wrzasq.pl.
 * @version 0.0.1
 * @since 0.0.1
 * @package ChillDev\Spintax
 */

namespace ChillDev\Spintax\Tests;

use PHPUnit_Framework_TestCase;

use ChillDev\Spintax\Content;

/**
 * @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
 * @copyright 2014 © by Rafał Wrzeszcz - Wrzasq.pl.
 * @version 0.0.1
 * @since 0.0.1
 * @package ChillDev\Spintax
 */
class ContentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function constructor()
    {
        $source = 'test';
        $node = new Content($source);

        $this->assertEquals($source, $node->__toString(), 'Content::__construct() should set initial node content.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function setContent()
    {
        $source = 'test';
        $node = new Content();
        $node->setContent($source);

        $this->assertEquals($source, $node->__toString(), 'Content::setContent() should set node content.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function addChild()
    {
        $source = 'test';
        $node = new Content();
        $node->addChild(new Content($source));

        $this->assertEquals('{' . $source . '}', $node->__toString(), 'Content::addChild() should set node child.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function setNext()
    {
        $source = 'test';
        $node = new Content();
        $node->setNext(new Content($source));

        $this->assertEquals($source, $node->__toString(), 'Content::setNext() should set node sibling.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function generateRandom()
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

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function generateDefined()
    {
        $root = $this->buildTree();

        $path = [0, 0];
        $this->assertEquals('I love PHP.', $root->generate($path), 'Content::generate() should generate particular variant if path is defined.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function generatePartial()
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

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function dump()
    {
        $root = $this->buildTree();

        $this->assertEquals('I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.', $root->dump(), 'Content::dump() should generate spintax string out of tree structure.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function getPaths()
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

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function explicitCount()
    {
        $node = new Content();
        $node->addChild(new Content());
        $node->addChild(new Content());
        $node->setNext(new Content());

        $this->assertEquals(2, $node->count(), 'Content::count() should calculate number of possible permutations.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function implicitCount()
    {
        $node = new Content();
        $node->addChild(new Content());
        $node->addChild(new Content());
        $node->setNext(new Content());

        $this->assertCount(2, $node, 'Content::count() should handle internal PHP counting.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function toStringConversion()
    {
        $source = 'test';
        $node = new Content($source);

        $this->assertEquals($source, $node->__toString(), 'Content::__toString() should dump spintax content.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function toStringCasting()
    {
        $source = 'test';
        $node = new Content($source);

        $this->assertEquals($source, (string) $node, 'Content::__toString() should handle conversion to string.');
    }

    /**
     * @return Content
     * @version 0.0.1
     * @since 0.0.1
     */
    protected function buildTree()
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
