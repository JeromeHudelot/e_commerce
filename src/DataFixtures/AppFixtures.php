<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Product;
use App\Entity\Categorie;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		$faker = Factory::create();
		
		$categories[]=null;
		
		for($i = 0; $i<10;$i++){
			
			$categories[$i] = new Categorie();
			$categories[$i]->setName($faker->words(2, true));
			$manager->persist($categories[$i]);
			
		}
        $manager->flush();
		
		for($i = 0; $i < 100; $i++){
			
			$product = new Product();
			$product->setName($faker->words(3, true));
			$product->setPrice($faker->randomFloat(2, 10, 200));
			$product->setStock($faker->numberBetween(0, 30));
			$product->setWeight($faker->numberBetween(1, 1000));
			$product->setDescription($faker->sentences(3, true));
			$product->setCreatedAt(new \Datetime());
			$product->setUpdatedAt(new \Datetime());
			$j = $faker->numberBetween(0, 9);
			$product->setCategorie($categories[$j]);
			$categories[$j]->addProduct($product);
			
			$manager->persist($product);
			
		}

        $manager->flush();
    }
}