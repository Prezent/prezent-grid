<?php

namespace Prezent\Grid\Tests\Extension\Core;

use Prezent\Grid\Tests\PHPUnit\GridFactoryProvider;

abstract class TypeTest extends \PHPUnit\Framework\TestCase
{
    use GridFactoryProvider;

    public function setUp(): void
    {
        $this->setUpGridFactory();
    }
}
