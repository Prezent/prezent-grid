<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Default grid factory
 *
 * @see GridFactory
 * @author Sander Marechal
 */
class DefaultGridFactory implements GridFactory
{
    /**
     * @var ElementTypeFactory
     */
    private $elementTypeFactory;

    /**
     * @var GridType[]
     */
    private $types;

    /**
     * Constructor
     *
     * @param ElementTypeFactory $elementTypeFactory
     * @param GridType[] $types
     */
    public function __construct(ElementTypeFactory $elementTypeFactory, array $types = [])
    {
        $this->elementTypeFactory = $elementTypeFactory;
        $this->types = $types;
    }

    /**
     * {@inheritDoc}
     */
    public function createBuilder($type = null, array $options = [])
    {
        $builder = $this->newBuilder();

        if ($type) {
            if (is_string($type)) {
                if (!isset($this->types[$type])) {
                    throw new InvalidArgumentException(sprintf('Grid type "%s" is not loaded', $type));
                }

                $type = $this->types[$type];
            }

            if (!($type instanceof GridType)) {
                throw new UnexpectedTypeException('string|' . GridType::class, $type);
            }

            $resolver = new OptionsResolver();
            $type->configureOptions($resolver);

            $options = $resolver->resolve($options);
            $type->buildGrid($builder, $options);
        }

        return $builder;
    }

    /**
     * {@inheritDoc}
     */
    public function createGrid($type, array $options = [])
    {
        return $this->createBuilder($type, $options)->getGrid();
    }

    /**
     * Create a new builder instance
     *
     * @return GridBuilder
     */
    protected function newBuilder()
    {
        return new GridBuilder($this->elementTypeFactory);
    }
}
