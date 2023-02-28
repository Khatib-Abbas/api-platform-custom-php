<?php

namespace App\Attribute;

use Attribute;

/**
 * SearchFilter is used for Activating Search Like based on Doctrine
 */
#[Attribute(\Attribute::TARGET_CLASS)]
class SearchFilter
{
    public function __construct(array $argument=[])
    {
    }

}