<?php

declare(strict_types=1);

namespace Movioza\Attribute\ControllerArgument;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Movioza\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * @implements AttributeHandlerInterface<MapEntity>
 */
readonly class MapEntityHandler implements AttributeHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(AttributeInterface $attribute, Request $request, ArgumentMetadata $argument): mixed
    {
        $routeParams = $request->attributes->get('_route_params', []);

        if (!array_key_exists($attribute->routeParam, $routeParams)) {
            throw new LogicException(sprintf('Route parameter "%s" was not found. Make sure the route contains the "%s" parameter required by the #[MapEntity] attribute.', $attribute->routeParam, $attribute->routeParam));
        }

        $repository = $this->em->getRepository($attribute->entity);
        $entity = $repository->findOneBy([
            $attribute->field => $routeParams[$attribute->routeParam],
        ]);

        if ($entity === null) {
            throw new NotFoundException('Resource not found.');
        }

        return $entity;
    }
}
