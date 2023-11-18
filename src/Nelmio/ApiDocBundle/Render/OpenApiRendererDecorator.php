<?php

declare(strict_types=1);

namespace PBaszak\ExtendedApiDoc\Nelmio\ApiDocBundle\Render;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Render\OpenApiRenderer;
use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\PathItem;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\Schema;
use OpenApi\Generator;
use PBaszak\ExtendedApiDoc\Nelmio\ApiDocBundle\Attributes\QueryParameters;
use Symfony\Component\Routing\RouterInterface;

class OpenApiRendererDecorator implements OpenApiRenderer
{
    public function __construct(
        private OpenApiRenderer $decorated,
        private RouterInterface $router,
    ) {
    }

    public function getFormat(): string
    {
        return $this->decorated->getFormat();
    }

    /** @param mixed[] $options */
    public function render(OpenApi $spec, array $options = []): string
    {
        $paths = $spec->paths;

        foreach ($paths as $path) {
            if ($path instanceof PathItem) {
                foreach ($path::$_nested as $operation) {
                    if (is_string($operation) && Generator::UNDEFINED !== $path->$operation) {
                        /** @var object{controller: string, method: string} $action */
                        $action = $this->mapOperationIdToControllerAndMethod($path->$operation->operationId);

                        $reflectionController = new \ReflectionClass($action->controller);
                        $reflectionMethod = $reflectionController->getMethod($action->method);
                        $attributus = $reflectionMethod->getAttributes(QueryParameters::class);

                        foreach ($attributus as $attribute) {
                            $queryParameters = $attribute->newInstance();
                            $class = $queryParameters->class;
                            $reflectionClass = new \ReflectionClass($class);

                            foreach ($reflectionClass->getProperties() as $property) {
                                $queryParameterAttr = $property->getAttributes(QueryParameter::class);
                                if (empty($queryParameterAttr)) {
                                    continue;
                                }

                                $queryParameter = $queryParameterAttr[0]->newInstance();
                                if (is_string($path->$operation->parameters)) {
                                    $path->$operation->parameters = [];
                                }
                                $path->$operation->parameters[] = new QueryParameter(
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->parameter ? $queryParameter->parameter : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->name ? $queryParameter->name ?? $property->getName() : $property->getName(),
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->description ? $queryParameter->description : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->in ? $queryParameter->in : 'query',
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->required ? $queryParameter->required : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->deprecated ? $queryParameter->deprecated : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->allowEmptyValue ? $queryParameter->allowEmptyValue : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->ref ? $queryParameter->ref : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->schema ? $queryParameter->schema : (class_exists($property->getType()->getName()) ?
                                        new Schema(ref: new Model(type: $property->getType()->getName())) :
                                        new Schema(type: $property->getType()->getName())),
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->example ? $queryParameter->example : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->examples ? $queryParameter->examples : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->content ? $queryParameter->content : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->style ? $queryParameter->style : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->explode ? $queryParameter->explode : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->allowReserved ? $queryParameter->allowReserved : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->spaceDelimited ? $queryParameter->spaceDelimited : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->pipeDelimited ? $queryParameter->pipeDelimited : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->x ? $queryParameter->x : null,
                                    '@OA\\Generator::UNDEFINED🙈' !== $queryParameter->attachables ? $queryParameter->attachable : null
                                );
                            }
                        }
                    }
                }
            }
        }

        return $this->decorated->render($spec, $options);
    }

    /**
     * @example post_app_presentation_export__invoke
     *
     * @return object{controller: string, method: string}
     */
    private function mapOperationIdToControllerAndMethod(string $operationId): object
    {
        [$method, $action] = explode('_', $operationId, 2);
        $route = $this->router->getRouteCollection()->get($action);
        if (null === $route) {
            throw new \RuntimeException(sprintf('Route "%s" not found', $action));
        }

        $action = $route->getDefault('_controller');
        $action = explode('::', $action, 2);
        
        return (object) [
            'controller' => $action[0],
            'method' => $action[1] ?? '__invoke',
        ];
    }
}
