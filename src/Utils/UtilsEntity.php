<?php

namespace App\Utils;

use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\ApiResource\UserApi;
use App\Entity\User;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class UtilsEntity
{

    /**
     * @throws ReflectionException
     */

    public  function setUserObjectWithReflexion(UserApi $data, User $user, string $method): bool
    {
        $needUpdate =false;
        $classDataAttributes = new ReflectionClass($data::class);
        foreach ($classDataAttributes->getProperties() as $dataClassAttribute){
            $dataProperty = new ReflectionProperty($data::class, $dataClassAttribute->name);
            if($dataProperty->getValue($data)){
                $classUserAttributes = new ReflectionClass($user::class);
                foreach ($classUserAttributes->getProperties() as $userClassAttribute){
                    if($userClassAttribute->name === $dataClassAttribute->name){
                        $userProperty = new ReflectionProperty($user::class, $dataClassAttribute->name);
                        if($method === Post::class ){
                            $needUpdate = true;
                            $userProperty->setValue($user,$dataProperty->getValue($data));
                        }
                        if($method === Patch::class || $method === Put::class){
                            if($userProperty->getValue($user) !== $dataProperty->getValue($data)){
                                $userProperty->setValue($user,$dataProperty->getValue($data));
                                $needUpdate =true;
                            }
                        }
                    }
                }
            }

        }
        return $needUpdate;
    }
}