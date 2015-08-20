<?php

namespace Prezent\Grid\Tests\PHPUnit;

use Prezent\Grid\DefaultElementTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\Extension\Core\CoreExtension;
use Prezent\Grid\VariableResolver\CallbackResolver;
use Prezent\Grid\VariableResolver\ChainResolver;
use Prezent\Grid\VariableResolver\PropertyPathResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait GridFactoryProvider
{
    protected $elementTypeFactory;
    protected $gridFactory;

    public function setUpGridFactory()
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $this->elementTypeFactory = new DefaultElementTypeFactory([
            new CoreExtension($accessor, new ChainResolver([
                new CallbackResolver(),
                new PropertyPathResolver($accessor)
            ])),
        ]);

        $this->gridFactory = new DefaultGridFactory($this->elementTypeFactory);
    }
}
