<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
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
        $users = [];
        for ($i = 1; $i <=10; $i++) {
            $user = User::create(
                sprintf("email+%d@example.com", $i),
                sprintf("name+%d", $i)
            );
            $user->setPassword($this->encoder->encodePassword($user, "password"));
            $manager->persist($user);

            $users[] = $user;
        }

        foreach($users as $user) {
            for ($j = 1; $j <=5; $j++) {
                $post = Post::createPost("Content", $user);

                shuffle($users);

                foreach (array_slice($users, 0, 5) as $userCanLike) {
                    $post->likedBy($userCanLike);
                }

                $manager->persist($post);

                for ($k = 1; $k <= 10; $k++) {
                    $comment = Comment::createComment(sprintf("Message %d", $k), $users[array_rand($users)], $post);

                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}