<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Painting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
       $faker= Factory::create('fr_FR');
       $painting = $manager->getRepository(Painting::class)->findAll();
       $countPainting = count($painting);

       for($i =1; $i<= 142; $i++){
          $comment = new Comment();
          $comment->setPseudo($faker->userName())
                  ->setPainting($painting[$faker->numberBetween(0, $countPainting -1)])
                  ->setContent($faker->paragraphs(3,true))
                  ->setCreatedAt(new \DateTimeImmutable())
                  ->setIsPublished($faker->boolean(55));
          $manager->persist($comment);
       }

        $manager->flush();
    }

   /**
    * @return string[]
    */
    public function getDependencies()
    {
       return [
          PaintingFixtures::class
       ];
    }
}
