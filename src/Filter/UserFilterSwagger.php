<?php

namespace App\Filter;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\PropertyInfo\Type;


final class UserFilterSwagger implements FilterInterface
{

    // This function is only used to hook in documentation generators (supported by Swagger and Hydra)
    public function getDescription(string $resourceClass): array
    {
        $description["firstName[]"] = [
            'property' => "firstName[]",
            'type' => Type::BUILTIN_TYPE_STRING,
            'required' => false,
            'description' => 'Filter user based on firstName using a like % LIKE %',
            'openapi' => [
                'example' => null,
                'allowReserved' => false,// if true, query parameters will be not percent-encoded
                'allowEmptyValue' => true,
                'explode' => false, // to be true, the type must be Type::BUILTIN_TYPE_ARRAY, ?product=blue,green will be ?product=blue&product=green
            ],
        ];
        $description["lastName[]"] = [
            'property' => "lastName[]",
            'type' => Type::BUILTIN_TYPE_STRING,
            'required' => false,
            'description' => 'Filter user based on lastName using a like % LIKE %',
            'openapi' => [
                'example' => null,
                'allowReserved' => false,// if true, query parameters will be not percent-encoded
                'allowEmptyValue' => true,
                'explode' => false, // to be true, the type must be Type::BUILTIN_TYPE_ARRAY, ?product=blue,green will be ?product=blue&product=green
            ],
        ];
        return $description;
    }

}