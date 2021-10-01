<?php

namespace App\DataFixtures;

use App\Entity\User;
Use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    /**
     * AppFixtures constructor
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) :void
    {
        for ($i = 1; $i <=10; $i++) {
            $user = User::create(
                sprintf("email+%d@example.com", $i),
                sprintf("name+%d", $i)
            );
            $user->setPassword($this->encoder->encodePassword($user, "password"));
            $manager->persist($user);
        }

        $manager->flush();
    }
}