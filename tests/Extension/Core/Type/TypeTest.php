<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

use Prezent\Grid\Tests\PHPUnit\GridFactoryProvider;

abstract class TypeTest extends \PHPUnit_Framework_TestCase
{
    use GridFactoryProvider;

    public function setUp()
    {
        $this->setUpGridFactory();
    }
}
