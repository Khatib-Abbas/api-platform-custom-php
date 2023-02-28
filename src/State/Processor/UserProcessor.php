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
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,private readonly EntityManagerInterface $manager)
    {
    }

    function iterateVisible() {
        echo "MyClass::iterateVisible:\n";
        foreach ($this as $key => $value) {
            print "$key => $value\n";
        }
    }

    /**
     * @throws ReflectionException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):User|null
    {

        if($operation instanceof Post ){
            $user = new User();
            $classDataAttributes = new ReflectionClass($data::class);
            foreach ($classDataAttributes->getProperties() as $dataClassAttribute){
                $dataProperty = new \ReflectionProperty($data::class, $dataClassAttribute->name);
                if($dataProperty->getValue($data)){
                    $classUserAttributes = new ReflectionClass($user::class);
                    foreach ($classUserAttributes->getProperties() as $userClassAttribute){
                        if($userClassAttribute->name === $dataClassAttribute->name){
                            $userProperty = new \ReflectionProperty($user::class, $dataClassAttribute->name);
                            $userProperty->setValue($user,$dataProperty->getValue($data));
                        }
                    }
                }
            }
            $this->userRepository->save( $user,true);
            return  $user;
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
            $needUpdate=false;
            $classDataAttributes = new ReflectionClass($data::class);
            foreach ($classDataAttributes->getProperties() as $dataClassAttribute){
                $dataProperty = new \ReflectionProperty($data::class, $dataClassAttribute->name);
                if($dataProperty->getValue($data)){
                    $classUserAttributes = new ReflectionClass($data::class);
                    foreach ($classUserAttributes->getProperties() as $userClassAttribute){
                        if($userClassAttribute->name === $dataClassAttribute->name){
                            $userProperty = new \ReflectionProperty($user::class, $dataClassAttribute->name);
                            if($userProperty->getValue($user) !== $dataProperty->getValue($data)){
                                $userProperty->setValue($user,$dataProperty->getValue($data));
                                $needUpdate =true;
                            }
                        }
                    }
                }
            }
            if ($needUpdate) $this->userRepository->save( $user,true);
           return  $user;
        }
        return  null;
    }
}