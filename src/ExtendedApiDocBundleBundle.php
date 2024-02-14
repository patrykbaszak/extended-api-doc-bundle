<?php

declare(strict_types=1);

namespace PBaszak\ExtendedApiDocBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ExtendedApiDocBundleBundle extends Bundle
{
    public const ALIAS = 'PBaszak.extended_api_doc_bundle';

    public function getContainerExtension(): ExtensionInterface
    {
        return $this->extension ??= new DependencyInjection\ExtendedApiDocBundleExtension();
    }
}
