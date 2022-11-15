<?php

namespace App\DataFixtures;

use App\Entity\Teams;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TeamsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($i=1; $i<=10; $i++) {
           $teams = new Teams();
           $teams->setName($faker->name)
                 ->setRole($faker->jobTitle())
                 ->setDescription($faker->words(15, true))
                 ->setImage($i .'.jpg');
           $manager->persist($teams);
        }

        $manager->flush();
    }
}
