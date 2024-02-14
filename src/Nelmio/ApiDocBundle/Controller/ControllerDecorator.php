<?php

declare(strict_types=1);

namespace PBaszak\ExtendedApiDoc\Nelmio\ApiDocBundle\Controller;

use Nelmio\ApiDocBundle\Controller\DocumentationController;
use Nelmio\ApiDocBundle\Controller\SwaggerUiController;
use Nelmio\ApiDocBundle\Controller\YamlDocumentationController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ControllerDecorator
{
    public function __construct(
        private YamlDocumentationController|DocumentationController|SwaggerUiController $decorated
    ) {
    }

    public function __invoke(Request $request, ?string $area = 'default'): Response
    {
        $area ??= 'default';

        return $this->decorated->__invoke($request, $area);
    }
}
