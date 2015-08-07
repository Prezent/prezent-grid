<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

use Prezent\Grid\DefaultColumnTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\Extension\Core\CoreExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class TypeTest extends \PHPUnit_Framework_TestCase
{
    protected $columnTypeFactory;
    protected $gridFactory;

    public function setUp()
    {
        $this->columnTypeFactory = new DefaultColumnTypeFactory([
            new CoreExtension(PropertyAccess::createPropertyAccessor()),
        ]);

        $this->gridFactory = new DefaultGridFactory($this->columnTypeFactory);
    }
}
