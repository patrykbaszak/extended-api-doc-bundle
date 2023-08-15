<?php

declare(strict_types=1);

namespace PBaszak\ExtendedApiDoc;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle.
 *
 * @author Patryk Baszak <patryk.baszak@gmail.com>
 */
class ExtendedApiDocBundle extends Bundle
{
    public const ALIAS = 'extendend_api_doc';

    public function getContainerExtension(): ExtensionInterface
    {
        return $this->extension ??= new DependencyInjection\ExtendedApiDocExtension();
    }
}
