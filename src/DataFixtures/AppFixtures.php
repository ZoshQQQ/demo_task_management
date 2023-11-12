<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Task;
use App\Entity\Status;
use App\Entity\Priority;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {

            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $manager->persist($user);

            for ($i = 0; $i < 5; $i++) {
                $task = new Task();
                $task->setTitle($faker->name);
                $task->setDescription($faker->realText());
                $task->setPriority($i % 2 ? Priority::High : Priority::Low);
                $task->setStatus(Status::Todo);
                $task->setCreatedAt(new \DateTimeImmutable("now"));
                $task->setUser($user);
                $manager->persist($task);
                $manager->flush();
            }
        }

        $manager->flush();

    }

    /**
     * @return array<array{string, string, string, string, array<string>}>
     */
    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', [User::ROLE_ADMIN]],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com', [User::ROLE_ADMIN]],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com', [User::ROLE_USER]],
        ];
    }
}
