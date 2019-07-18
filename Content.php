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

namespace bjoernffm\Spintax;

use Countable;

/**
 * Spintax tree node.
 *
 * @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
 * @copyright 2014 © by Rafał Wrzeszcz - Wrzasq.pl.
 * @version 0.0.1
 * @since 0.0.1
 * @package ChillDev\Spintax
 */
class Content implements Countable
{
    /**
     * Node raw value.
     *
     * @var string
     * @version 0.0.1
     * @since 0.0.1
     */
    protected $content;

    /**
     * Possible sub-contents.
     *
     * @var self[]
     * @version 0.0.1
     * @since 0.0.1
     */
    protected $children = [];

    /**
     * Next node value.
     *
     * @var self
     * @version 0.0.1
     * @since 0.0.1
     */
    protected $next;

    /**
     * Builds node representaiton for given string vlaue.
     *
     * @param string $content Text content.
     * @version 0.0.1
     * @since 0.0.1
     */
    public function __construct($content = '')
    {
        $this->setContent($content);
    }

    /**
     * Sets content value.
     *
     * @param string $content Text content.
     * @return self Self instance.
     * @version 0.0.1
     * @since 0.0.1
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Adds child node.
     *
     * @param self $child Sub-content.
     * @return self Self instance.
     * @version 0.0.1
     * @since 0.0.1
     */
    public function addChild(Content $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Sets next node.
     *
     * @param self $next Next node.
     * @return self Self instance.
     * @version 0.0.1
     * @since 0.0.1
     */
    public function setNext(Content $next)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * Generates plain content (optionally based on pre-defined path).
     *
     * @param int[] &$path Path to use for the content.
     * @param int &$index Internal pointer for path array.
     * @return string Generated content.
     * @version 0.0.1
     * @since 0.0.1
     */
    public function generate(array &$path = [], &$index = 0)
    {
        $content = $this->content;

        // pick child value
        if (!empty($this->children)) {
            // pick random child
            if (!isset($path[$index])) {
                $path[$index] = \rand(0, count($this->children) - 1);
            }
            $option = $path[$index];
            ++$index;
            $content .= $this->children[$option]->generate($path, $index);
        }

        // continue further
        if (isset($this->next)) {
            $content .= $this->next->generate($path, $index);
        }

        return $content;
    }

    /**
     * Dumps source spintax format.
     *
     * @return string Merged content.
     * @version 0.0.1
     * @since 0.0.1
     */
    public function dump()
    {
        $content = $this->content;

        // dump all possible children paths
        if (!empty($this->children)) {
            $options = [];
            foreach ($this->children as $child) {
                $options[] = $child->dump();
            }
            $content .= '{' . implode('|', $options) . '}';
        }

        // continue further
        if (isset($this->next)) {
            $content .= $this->next->dump();
        }

        return $content;
    }

    /**
     * Returns list of all possible paths.
     *
     * @return array List of all possible paths.
     * @version 0.0.1
     * @since 0.0.1
     */
    public function getPaths()
    {
        if (empty($this->children)) {
            return [[]];
        } else {
            // list of paths, from which each is list of steps
            $paths = [];

            foreach ($this->children as $key => $child) {
                foreach ($child->getPaths() as $path) {
                    \array_unshift($path, $key);
                    $paths[] = $path;
                }
            }

            return $paths;
        }
    }

    /**
     * Returns number of possible combinations.
     *
     * @return int Number of possible combinations.
     * @version 0.0.1
     * @since 0.0.1
     */
    public function count()
    {
        // self-caontained at least
        $count = 1;

        // all sub-trees
        if (!empty($this->children)) {
            foreach ($this->children as $child) {
                $count += \count($child);
            }
            --$count;
        }

        // also use further content
        if (isset($this->next)) {
            $count *= \count($this->next);
        }

        return $count;
    }

    /**
     * Returns plain representation.
     *
     * @return string Merged content.
     * @version 0.0.1
     * @since 0.0.1
     */
    public function __toString()
    {
        return $this->dump();
    }
}
