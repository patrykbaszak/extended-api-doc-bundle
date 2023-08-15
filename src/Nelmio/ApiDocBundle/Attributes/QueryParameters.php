<?php

namespace PBaszak\ExtendedApiDoc\Nelmio\ApiDocBundle\Attributes;

use Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class QueryParameters
{
    public function __construct(
        public string $class,
    ) {
    }
}
