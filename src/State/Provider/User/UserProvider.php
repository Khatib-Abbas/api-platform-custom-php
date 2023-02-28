<?php

namespace App\State\Provider\User;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class UserProvider implements ProviderInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RequestStack $request)
    {

    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        if ($operation instanceof CollectionOperationInterface) {
            $page = (int) $this->request->getCurrentRequest()->query->get('page', 1);
            $qb = $this->userRepository->getOrCreateQueryBuilder();
            if(count($operation->getFilters()) && isset($context["filters"])){
                $this->userRepository->searchFilter($qb,$context["filters"]);
            }
            return $this->userRepository->filterPerPage($qb,$page);
        }
        return $this->userRepository->find($context['uri_variables']['id']);
    }
}
