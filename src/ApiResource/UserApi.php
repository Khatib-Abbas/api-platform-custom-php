<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Attribute\SearchFilter;
use App\Enum\SearchFilterMethodSearchEnum;
use App\Filter\UserFilterSwagger;
use App\State\Processor\UserProcessor;
use App\State\Provider\User\UserProvider;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource (
    shortName: "users",
    description: "A classic user with CRUD",
    operations: [
        new Get(
            uriTemplate: '/users/{id}', provider: UserProvider::class
        ),
       new GetCollection(provider: UserProvider::class),
       new Post(
           validationContext: ['groups' => ['Default', 'postValidation']],
           processor: UserProcessor::class
       ),
       new Put(
           validationContext: ['groups' => ['Default', 'putValidation']],
           provider: UserProvider::class,
           processor: UserProcessor::class),
       new Patch(
           validationContext: ['groups' => ['Default', 'patchValidation']],
           provider: UserProvider::class,
           processor: UserProcessor::class
       ),
       new Delete(provider: UserProvider::class, processor: UserProcessor::class)
    ],
    extraProperties: [
        'standard_put' => true,
    ],
)]
#[ApiFilter(UserFilterSwagger::class)]
#[SearchFilter([
    "firstName"=>SearchFilterMethodSearchEnum::SEARCH_FILTER_METHOD_SEARCH_PARTIAL,
    "lastName"=>SearchFilterMethodSearchEnum::SEARCH_FILTER_METHOD_SEARCH_PARTIAL
])]

class UserApi
{
    #[ApiProperty(identifier: true)]
    private ?Uuid $id=null;

    #[ApiProperty(description: 'the firstName of the user')]
    #[Assert\NotBlank(message: 'firstName cannot be equal to a blank string, a blank array, false or null', groups: ["postValidation"])]
    #[Assert\Length(
        max: 255,
        minMessage: 'firstName must be at least {{ limit }} characters long',
        groups: ["postValidation","patchValidation","putValidation"]
    )]
    private ?string $firstName=null;

    #[ApiProperty(description: 'the lastName of the user')]
    #[Assert\NotBlank(message: 'lastName cannot be equal to a blank string, a blank array, false or null', groups: ["postValidation"])]
    #[Assert\Length(
        max: 255,
        minMessage: 'lastName must be at least {{ limit }} characters long',
        groups: ["postValidation","patchValidation","putValidation"]
    )]
    private ?string $lastName=null;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
