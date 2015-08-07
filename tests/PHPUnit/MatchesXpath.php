<?php

namespace Prezent\Grid\Tests\PHPUnit;

class MatchesXpath extends \PHPUnit_Framework_Constraint
{
    /**
     * @var string
     */
    private $expression;

    /**
     * @var int
     */
    private $count;

    /**
     * Constructor
     *
     * @param mixed $expression
     * @param mixed $count
     */
    public function __construct($expression, $count = 1)
    {
        parent::__construct();
        $this->expression = $expression;
        $this->count = $count;
    }

    public function matches($html)
    {
        $dom = new \DomDocument('UTF-8');
        try {
            // Wrap in <root> node so we can load HTML with multiple tags at
            // the top level
            $dom->loadXML('<root>'.$html.'</root>');
        } catch (\Exception $e) {
            $this->fail(sprintf(
                "Failed loading HTML:\n\n%s\n\nError: %s",
                $html,
                $e->getMessage()
            ));
        }
        $xpath = new \DOMXPath($dom);
        $nodeList = $xpath->evaluate('/root'.$this->expression);

        return $nodeList->length == $this->count;
    }

    public function toString()
    {
        return 'matches xpath \'' . $this->expression . '\' ' . $this->count . ' times';
    }
}
