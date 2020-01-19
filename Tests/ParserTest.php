<?php

declare(strict_types=1);

use bjoernffm\Spintax\Parser;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    public function parseDataProvider(): array
    {
        return [
            ['I {love {PHP|Java|C|C++|JavaScript|Python}|{hate|can\'t stand} Ruby}.'],
            ['I {love|hate} {PHP|Java|C|C++|JavaScript|Python}.'],
            ['{a|b{c|d|e}{f|g}}{h|i|{j|k}|{l|m|n}}'],
        ];
    }

    public function testReplicate(): void
    {
        $source = 'I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.';
        $this->assertEquals('I love PHP.', Parser::replicate($source, '0,0'));
        $this->assertEquals('I love PHP.', Parser::replicate($source, [0, 0]));

        $spintax = Parser::parse($source);
        $this->assertEquals('I love PHP.', Parser::replicate($spintax, '0,0'));
        $this->assertEquals('I love PHP.', Parser::replicate($spintax, [0, 0]));
    }
}
