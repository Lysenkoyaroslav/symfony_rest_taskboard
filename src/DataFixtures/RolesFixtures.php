<?php

namespace App\DataFixtures;

use App\Entity\Roles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RolesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $role = new Roles();
        $role->setRoles('User');
        $manager->persist($role);

        $role = new Roles();
        $role->setRoles('Admin');
        $manager->persist($role);

        $manager->flush();
    }
}
