<?php

namespace Prezent\Grid\Tests\PHPUnit;

use Prezent\Grid\DefaultColumnTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\Extension\Core\CoreExtension;
use Prezent\Grid\VariableResolver\CallbackResolver;
use Prezent\Grid\VariableResolver\ChainResolver;
use Prezent\Grid\VariableResolver\PropertyPathResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait GridFactoryProvider
{
    protected $columnTypeFactory;
    protected $gridFactory;

    public function setUpGridFactory()
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $this->columnTypeFactory = new DefaultColumnTypeFactory([
            new CoreExtension($accessor, new ChainResolver([
                new CallbackResolver(),
                new PropertyPathResolver($accessor)
            ])),
        ]);

        $this->gridFactory = new DefaultGridFactory($this->columnTypeFactory);
    }
}
