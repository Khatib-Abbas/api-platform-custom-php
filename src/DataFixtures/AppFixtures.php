<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['firstName'=>'abbas','lastName'=>'khatib']);
        UserFactory::createOne(['firstName'=>'sébastien','lastName'=>'mina']);
        UserFactory::createMany(100);
        $manager->flush();
    }
}
