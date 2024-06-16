<?php

declare(strict_types=1);

use bjoernffm\Spintax\Parser;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    public function testReplicate(): void
    {
        $source = 'I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.';
        $this->assertEquals('I love PHP.', Parser::replicate($source, '0,0'));
        $this->assertEquals('I love PHP.', Parser::replicate($source, [0, 0]));

        $spintax = Parser::parse($source);
        $this->assertEquals('I love PHP.', Parser::replicate($spintax, '0,0'));
        $this->assertEquals('I love PHP.', Parser::replicate($spintax, [0, 0]));
        $this->assertEquals('I love Java.', Parser::replicate($spintax, [0, 1]));
        $this->assertEquals('I love C.', Parser::replicate($spintax, [0, 2]));
        $this->assertEquals('I love C++.', Parser::replicate($spintax, [0, 3]));
        $this->assertEquals('I love JavaScript.', Parser::replicate($spintax, [0, 4]));
        $this->assertEquals('I love Python.', Parser::replicate($spintax, [0, 5]));
        $this->assertEquals('I hate Ruby.', Parser::replicate($spintax, [1, 0]));
    }

    public function testWithoutBraces(): void
    {
        $result = Parser::parse('john|doe')->generate();
        $this->assertEquals('john|doe', $result);

        $spintax = Parser::parse('john|doe {a|b}');
        $this->assertEquals('john|doe a', Parser::replicate($spintax, [0]));
        $this->assertEquals('john|doe b', Parser::replicate($spintax, [1]));

        $spintax = Parser::parse('{a|b} john|doe');
        $this->assertEquals('a john|doe', Parser::replicate($spintax, [0]));
        $this->assertEquals('b john|doe', Parser::replicate($spintax, [1]));

        $spintax = Parser::parse('{a|b|c} john|doe|foo');
        $this->assertEquals('a john|doe|foo', Parser::replicate($spintax, [0]));
        $this->assertEquals('b john|doe|foo', Parser::replicate($spintax, [1]));
        $this->assertEquals('c john|doe|foo', Parser::replicate($spintax, [2]));

        $spintax = Parser::parse('{a|{b|c}} john|doe');
        $this->assertEquals('a john|doe', Parser::replicate($spintax, [0, 0]));
        $this->assertEquals('b john|doe', Parser::replicate($spintax, [1, 0]));
        $this->assertEquals('c john|doe', Parser::replicate($spintax, [1, 1]));
    }
}
