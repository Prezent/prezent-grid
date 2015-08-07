<?php

namespace Prezent\Grid\Tests\PHPUnit;

use Prezent\Grid\DefaultColumnTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\Extension\Core\CoreExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait GridFactoryProvider
{
    protected $columnTypeFactory;
    protected $gridFactory;

    public function setUpGridFactory()
    {
        $this->columnTypeFactory = new DefaultColumnTypeFactory([
            new CoreExtension(PropertyAccess::createPropertyAccessor()),
        ]);

        $this->gridFactory = new DefaultGridFactory($this->columnTypeFactory);
    }
}
