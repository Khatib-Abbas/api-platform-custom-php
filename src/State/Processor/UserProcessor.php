<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\Put;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\ReflexionClass\UserReflexClass;
use App\Utils\UtilsEntity;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $manager,
        private readonly UtilsEntity $utilsEntity
    )
    {
    }


    /**
     * @throws ReflectionException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):User|null
    {

        if($operation instanceof Post ){
            $user = new User();
            if($this->utilsEntity->setUserObjectWithReflexion($data, $user, $operation::class)){
                $this->userRepository->save($user, true);
            }
            return $user;
        }
        if($operation instanceof Delete ){
            /**
             * @var User $userPrevious
             */
            $userPrevious =  $context['previous_data'];
            /**
             * find the Object
             * @var User $user
             */
            $user = $this->manager->getRepository(User::class)->find($userPrevious->getId());
            $this->userRepository->remove( $user,true);
        }
        if ($operation instanceof Patch || $operation instanceof Put) {
            /**
             * @var User $userPrevious
             */
            $userPrevious =  $context['previous_data'];
            /**
             * find the Object
             * @var User $user
             */
            $user = $this->manager->getRepository(User::class)->find($userPrevious->getId());
            if($this->utilsEntity->setUserObjectWithReflexion($data, $user, $operation::class)){
                $this->userRepository->save( $user,true);
            }
            return $user;
        }
        return  null;
    }
}