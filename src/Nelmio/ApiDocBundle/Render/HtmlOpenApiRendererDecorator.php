<?php

declare(strict_types=1);

namespace PBaszak\ExtendedApiDoc\Nelmio\ApiDocBundle\Render;

use Nelmio\ApiDocBundle\Render\Html\AssetsMode;
use Nelmio\ApiDocBundle\Render\Html\HtmlOpenApiRenderer;
use Nelmio\ApiDocBundle\Render\OpenApiRenderer;
use OpenApi\Annotations\OpenApi;
use Twig\Environment;

class HtmlOpenApiRendererDecorator implements OpenApiRenderer
{
    public function __construct(
        private HtmlOpenApiRenderer $decorated,
        private Environment|\Twig_Environment $twig
    ) {
    }

    public function getFormat(): string
    {
        return $this->decorated->getFormat();
    }

    /** @param mixed[] $options */
    public function render(OpenApi $spec, array $options = []): string
    {
        $options += [
            'assets_mode' => AssetsMode::CDN,
            'swagger_ui_config' => [],
        ];

        return $this->twig->render(
            'documentation.html.twig',
            [
                'swagger_data' => ['spec' => json_decode($spec->toJson(), true)],
                'assets_mode' => $options['assets_mode'],
                'swagger_ui_config' => $options['swagger_ui_config'],
            ]
        );
    }
}
