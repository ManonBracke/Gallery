<?php

namespace App\DataFixtures;

use App\Entity\Technical;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TechnicalFixtures extends Fixture
{
   public array $technical = ['Huile sur toile', 'Pastel', 'Pointillism' , 'Aquarelle', 'Peinture acrylique', 'Tempera'];
   public function load(ObjectManager $manager): void
   {

      foreach ($this->technical as $data) {
         $technical = new Technical();
         $technical->setName($data);
         $manager->persist($technical);
      }

      $manager->flush();
   }
}
