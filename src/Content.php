<?php

namespace bjoernffm\Spintax;

use Countable;

class Content implements Countable
{
    protected $content;

    protected $children = [];

    protected $next;

    /**
     * Builds node representaiton for given string value.
     *
     * @param string $content Text content.
     */
    public function __construct(string $content = '')
    {
        $this->setContent($content);
    }

    /**
     * Sets content value.
     *
     * @param string $content Text content.
     *
     * @return self Self instance.
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Adds child node.
     *
     * @param self $child Sub-content.
     *
     * @return self Self instance.
     */
    public function addChild(self $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Sets next node.
     *
     * @param self $next Next node.
     *
     * @return self Self instance.
     */
    public function setNext(self $next): self
    {
        $this->next = $next;

        return $this;
    }

    /**
     * Generates plain content (optionally based on pre-defined path).
     *
     * @param int[] &$path  Path to use for the content.
     * @param int   &$index Internal pointer for path array.
     *
     * @return string Generated content.
     */
    public function generate(array &$path = [], int &$index = 0): string
    {
        $content = $this->content;

        // pick child value
        if (!empty($this->children)) {
            // pick random child
            if (!isset($path[$index])) {
                $path[$index] = \rand(0, count($this->children) - 1);
            }
            $option = $path[$index];
            $index++;
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
     */
    public function dump(): string
    {
        $content = $this->content;

        // dump all possible children paths
        if (!empty($this->children)) {
            $options = [];
            foreach ($this->children as $child) {
                $options[] = $child->dump();
            }
            $content .= '{'.implode('|', $options).'}';
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
     */
    public function getPaths(): array
    {
        if (empty($this->children)) {
            return [[]];
        }

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

    /**
     * Returns number of possible combinations.
     *
     * @return int Number of possible combinations.
     */
    public function count(): int
    {
        // self-caontained at least
        $count = 1;

        // all sub-trees
        if (!empty($this->children)) {
            foreach ($this->children as $child) {
                $count += \count($child);
            }
            $count--;
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
     */
    public function __toString(): string
    {
        return $this->dump();
    }
}
