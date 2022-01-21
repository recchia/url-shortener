<?php

namespace App\DataFixtures;

use App\Factory\ShortUrlFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ShortUrlFactory::createMany(3);

        $manager->flush();
    }
}
