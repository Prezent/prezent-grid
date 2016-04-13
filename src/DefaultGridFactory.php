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
     * @var GridTypeFactory
     */
    private $gridTypeFactory;

    /**
     * @var ElementTypeFactory
     */
    private $elementTypeFactory;

    /**
     * Constructor
     *
     * @param GridTypeFactory $gridTypeFactory
     * @param ElementTypeFactory $elementTypeFactory
     */
    public function __construct(GridTypeFactory $gridTypeFactory, ElementTypeFactory $elementTypeFactory)
    {
        $this->gridTypeFactory = $gridTypeFactory;
        $this->elementTypeFactory = $elementTypeFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function createBuilder($type = 'grid', array $options = [])
    {
        if ($type instanceof GridType) {
            $type = $this->gridTypeFactory->resolveType($type);
        } elseif (is_string($type)) {
            $type = $this->gridTypeFactory->getType($type);
        }

        if (!($type instanceof ResolvedGridType)) {
            throw new UnexpectedTypeException('string|' . GridType::class . '|' . ResolvedGridType::class, $type);
        }

        $builder = $this->newBuilder($type, $options);

        $type->buildGrid($builder, $builder->getOptions());

        return $builder;
    }

    /**
     * {@inheritDoc}
     */
    public function createGrid($type = 'grid', array $options = [])
    {
        return $this->createBuilder($type, $options)->getGrid();
    }

    /**
     * Create a new builder instance
     *
     * @return GridBuilder
     */
    protected function newBuilder(ResolvedGridType $type, array $options = [])
    {
        $options = $type->getOptionsResolver()->resolve($options);

        return new GridBuilder($type, $this->elementTypeFactory, $options);
    }
}
