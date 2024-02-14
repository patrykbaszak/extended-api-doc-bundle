<?php

declare(strict_types=1);

namespace PBaszak\ExtendedApiDoc\Nelmio\ApiDocBundle\Render;

use Nelmio\ApiDocBundle\Render\OpenApiRenderer;
use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;

class OpenApiRendererSortDecorator implements OpenApiRenderer
{
    public function __construct(private OpenApiRenderer $decorated)
    {
    }

    public function getFormat(): string
    {
        return $this->decorated->getFormat();
    }

    /** @param mixed[] $options */
    public function render(OpenApi $spec, array $options = []): string
    {
        $this->sortPathsByTag($spec);

        return $this->decorated->render($spec, $options);
    }

    private function sortPathsByTag(OpenApi $spec): void
    {
        $pathsByTag = [];
        foreach ($spec->paths as $pathItem) {
            $path = $pathItem->path;
            foreach ($pathItem::$_nested as $operation) {
                if (is_string($operation) && Generator::UNDEFINED !== $pathItem->$operation) {
                    if (is_array($pathItem->$operation->tags)) {
                        foreach ($pathItem->$operation->tags as $tag) {
                            $pathsByTag[$tag][$path] = $pathItem;
                        }
                    } else {
                        $pathItem->$operation->tags = ['default'];
                        $pathsByTag['default'][$path] = $pathItem;
                    }
                }
            }
        }

        ksort($pathsByTag, SORT_NATURAL);

        $spec->paths = [];
        foreach ($pathsByTag as $tag => $paths) {
            ksort($paths, SORT_NATURAL);

            $spec->paths = array_merge($spec->paths, array_values($paths));
        }
    }
}
