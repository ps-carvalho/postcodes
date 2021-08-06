<?php

namespace App\DataFixtures;

use App\Entity\Location;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $location = new Location();
         $location->setId(1);
         $location->setPostCode('bh192qt');
         $location->setEastings(12345);
         $location->setNorthings(12345);
         $location->setLongitude();
         $location->setLatitude();
         $manager->persist($location);
        $manager->flush();
    }
}
