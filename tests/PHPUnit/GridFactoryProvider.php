<?php

namespace Prezent\Grid\Tests\PHPUnit;

use Prezent\Grid\DefaultElementTypeFactory;
use Prezent\Grid\DefaultGridTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\Extension\Core\CoreExtension;
use Prezent\Grid\VariableResolver\CallbackResolver;
use Prezent\Grid\VariableResolver\ChainResolver;
use Prezent\Grid\VariableResolver\PropertyPathResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait GridFactoryProvider
{
    protected $gridTypeFactory;
    protected $elementTypeFactory;
    protected $gridFactory;

    public function setUpGridFactory()
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $extension = new CoreExtension($accessor, new ChainResolver([
            new CallbackResolver(),
            new PropertyPathResolver($accessor)
        ]));

        $this->gridTypeFactory = new DefaultGridTypeFactory([$extension]);
        $this->elementTypeFactory = new DefaultElementTypeFactory([$extension]);

        $this->gridFactory = new DefaultGridFactory($this->gridTypeFactory, $this->elementTypeFactory);
    }
}
