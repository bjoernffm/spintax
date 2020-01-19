<?php

namespace bjoernffm\Spintax;

class Parser
{
    /**
     * Parses spintax article.
     *
     * @param string $string Source content.
     *
     * @return Content Parsed spintax tree.
     */
    public static function parse($string)
    {
        $root = new Content();

        // initialize parser nodes
        $current = $root;
        $parent = $root;
        $parents = [];
        $tokens = '{}|';

        // loop through the string looking for spintax tokens
        $part = strpbrk($string, $tokens);
        while (false !== $part) {
            $token = $part[0];
            $current->setContent(substr($string, 0, -strlen($part)));
            $string = substr($part, 1);

            switch ($token) {
                // start of new choice
                case '{':
                    // stack parent
                    $parents[] = $parent;
                    $parent = $current;
                // next option
                case '|':
                    // create child node
                    $current = new Content();
                    $parent->addChild($current);
                    break;

                // end of subset
                case '}':
                    // move forward
                    $current = new Content();
                    $parent->setNext($current);

                    // un-stack parent
                    $parent = array_pop($parents);
                    break;
            }

            $part = strpbrk($string, $tokens);
        }

        $current->setContent($string);

        return $root;
    }

    /**
     * Reproduces article content for specified path.
     *
     * @param string|Content $content Source content (or already parsed spintax tree).
     * @param string|int[]   $path    Path to use to generate the article.
     *
     * @return string Generated article content.
     */
    public static function replicate($content, $path)
    {
        // parse content
        if (!$content instanceof Content) {
            $content = static::parse($content);
        }

        // build path
        if (!is_array($path)) {
            $path = explode(',', $path);
        }

        return $content->generate($path);
    }
}
