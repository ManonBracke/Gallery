<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Painting;
use App\Entity\Technical;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PaintingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
         $faker = Factory::create('fr_FR');
         $slugify = new Slugify();
         $category = $manager->getRepository(Category::class)->findAll();
         $technical = $manager->getRepository(Technical::class)->findAll();
         $nbCat = count($category);
         $nbTec = count($technical);


         for($i=1; $i <= 25; $i++) {
            $painting= new Painting();
            $title = $faker->words($faker->numberBetween(2,3), true);
            $painting->setTitle($title)
                     ->setDescription($faker->words(50, true))
                     ->setCreatedAt(new \DateTimeImmutable(date_format($faker->dateTimeAD(), 'Y/m/d').' '. $faker->time())) // regarder pour mettre la date de creation !
                     ->setHeight($faker->numberBetween(100,500), true)
                     ->setWidth($faker->numberBetween(100,500), true)
                     ->setImage($i . '.jpg')
                     ->setCategory($category[$faker->numberBetween(0, $nbCat-1)])
                     ->setTechnical($technical[$faker->numberBetween(0, $nbTec-1)])
                     ->setIsPublished($faker->boolean(90))
                     ->setSlug($slugify->slugify($title))
                     ->setAuthor($faker->name);
            $manager->persist($painting);
         }
        $manager->flush();
    }
    public function getDependencies()
    {
       return [
          CategoryFixtures::class,
          TechnicalFixtures::class
       ];
    }
}
