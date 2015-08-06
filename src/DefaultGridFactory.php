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
     * @var ColumnTypeFactory
     */
    private $columnTypeFactory;

    /**
     * @var GridType[]
     */
    private $types;

    /**
     * Constructor
     *
     * @param ColumnTypeFactory $columnTypeFactory
     * @param GridType[] $types
     */
    public function __construct(ColumnTypeFactory $columnTypeFactory, array $types = [])
    {
        $this->columnTypeFactory = $columnTypeFactory;
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
                    throw new InvalidArgumentException('Grid type "%s" is not loaded');
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
        return $this->createBuilder($type)->getGrid();
    }

    /**
     * Create a new builder instance
     *
     * @return GridBuilder
     */
    protected function newBuilder()
    {
        return new GridBuilder($this->columnTypeFactory);
    }
}
