<?php

namespace App\Tests\Unit\Entity;

use App\ApiResource\UserApi;
use App\Entity\User;

use PHPUnit\Framework\TestCase;

class  UserTest extends TestCase
{

    /**
     * @dataProvider
     */
    public function testIfUserIsDeletedFrom360LearningBetweenZeroAndOne(IsDeletedFrom360Learning $isDeletedFrom360Learning, bool $expectedIsDeletedFrom360Learning ):void
    {
        $user = new User();
        $user->setIsDeletedFrom360Learning($isDeletedFrom360Learning);
        self::assertSame(boolval($expectedIsDeletedFrom360Learning), $user->isInRangeFrom360Learning());
    }


    // PROVIDERS
    public function userIsDeletedFrom360LearningProvider(): \Generator
    {
        $user1 = new User();
        $user1->setLastName('abbas');
        $user1->setLastName('khatib');
        yield 'Not deleted  User from 360 Learning' => [
            $user1,
            true,
        ];
        yield 'Deleted User from 360 Learning' => [
            IsDeletedFrom360Learning::DELETED,
            true
        ];
    }


}