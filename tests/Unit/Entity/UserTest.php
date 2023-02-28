<?php

namespace App\Tests\Unit\Entity;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\ApiResource\UserApi;
use App\Entity\User;
use App\Enum\SearchFilterMethodSearchEnum;
use App\Utils\UtilsEntity;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class  UserTest extends TestCase
{

    /**
     * @dataProvider  searchFilterEnumProvider
     */
    public function testTheValidityOfSearchFilterMethodSearchEnum(SearchFilterMethodSearchEnum $isValueEnum, string $expectedEnum ):void
    {
       self::assertSame($isValueEnum->value, $expectedEnum);
    }

    /**
     * @dataProvider userUtilsEntityProvider
     * @throws ReflectionException
     */
    public function testSetUserObjectWithReflexionOfTheUtilsEntity(User $user,UserApi $userApi,$method , bool $expectedStatus){
        $userTestUtils = new UtilsEntity();
        self::assertSame($userTestUtils->setUserObjectWithReflexion( $userApi,$user,$method), $expectedStatus);
    }

    // PROVIDERS
    public function searchFilterEnumProvider(): \Generator
    {
        yield 'search filter enum of type end' => [
            SearchFilterMethodSearchEnum::SEARCH_FILTER_METHOD_SEARCH_END,
            'end',
        ];
        yield 'search filter enum of type partial' => [
            SearchFilterMethodSearchEnum::SEARCH_FILTER_METHOD_SEARCH_PARTIAL,
            'partial'
        ];
        yield 'search filter enum of type start' => [
            SearchFilterMethodSearchEnum::SEARCH_FILTER_METHOD_SEARCH_START,
            'start'
        ];
    }

    public function userUtilsEntityProvider(): \Generator
    {

        yield 'userProvider with method Put' => [
            $this->generateRandomUserEntity(),
            $this->generateRandomUserApi(),
            Put::class,
            true,
        ];
        yield 'userProvider with method Patch' => [
            $this->generateRandomUserEntity(),
            $this->generateRandomUserApi(),
            Patch::class,
            true,
        ];
        yield 'userProvider with method Post' => [
            $this->generateRandomUserEntity(),
            $this->generateRandomUserApi(),
            Post::class,
            true,
        ];

    }

    public function  generateRandomUserEntity(): User
    {
        // use the factory to create a Faker\Generator instance
        $faker = Factory::create();
        $userOne = new User();
        $userOne->setLastName($faker->lastName());
        $userOne->setFirstName($faker->firstNAme());
        return $userOne;
    }
    public function  generateRandomUserApi(): UserApi
    {
        $faker = Factory::create();
        $userApiOne = new UserApi();
        $userApiOne->setLastName($faker->lastName());
        $userApiOne->setFirstName($faker->firstNAme());

        return $userApiOne;
    }


}