<?php

declare(strict_types=1);

namespace App\Domain\Admin\Commands;

use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:create-admin';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct(
            self::$defaultName,
        );
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates an admin account')
            ->setHelp('This command allows you to create a new admin account...')
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'The username of the admin account',
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'The password of the admin account',
            )
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $output->writeln('Starting the creation of a new admin account...');

        $userRepository = $this->entityManager
            ->getRepository(User::class)
        ;

        $user = $userRepository->findBy(['email' => $input->getArgument('email')]);

        if ($user) {
            $output->writeln('An account with this email already exists.');
            $output->writeln('Ending the creation of a new admin account.');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($input->getArgument('email'));
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $input->getArgument('password'),
            ),
        );
        $user->setRoles(['ROLE_ADMIN']);
        $user->setVerified(true);

        $this->entityManager
            ->persist($user);
        $this->entityManager
            ->flush();

        $output->writeln('The admin account for ' . $input->getArgument('email') . ' has been created.');
        $output->writeln('Ending the creation of a new admin account.');
        return Command::SUCCESS;
    }
}
