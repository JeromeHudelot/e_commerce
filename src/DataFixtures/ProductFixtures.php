<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class Product extends Fixture
{
    public function load(ObjectManager $manager)
    {
		$faker = Factory::create();
		for($i = 0; $i < 100; $i++){
			
			$product = new Product();
			$product->setName($faker->words(3, true));
			$product->setPrice($faker->randomFloat(2, 10, 200));
			$product->setStock($faker->numberBetween(0, 30));
			$product->setDescription($faker->sentences(3, true));
			$product->setCreatedAt(new \Datetime());
			$product->setUpdatedAt(new \Datetime());
			
			$manager->persist($product);
			
		}

        $manager->flush();
    }
}