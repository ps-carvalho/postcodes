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
         $location->setPostCode('WC1A1AB');
         $location->setEastings(530186);
         $location->setNorthings(181384);
         $location->setLongitude();
         $location->setLatitude();
         $manager->persist($location);

        $manager->flush();
    }
}
