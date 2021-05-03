<?php

namespace App\Infrastructure\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $review = Review::create(
                [
                    'title'       => 'Review ' . $i,
                    'content'     => 'Lorem ipsum dolor sit amet',
                    'rate'        => $i,
                    'source'      => Review::SOURCE_AMAZON,
                    'author'      => "User$i J*** S****",
                    'created_at'  => "2020-0$i-0$i",
                ]
            );

            $manager->persist($review);
        }

        $manager->flush();
    }
}
