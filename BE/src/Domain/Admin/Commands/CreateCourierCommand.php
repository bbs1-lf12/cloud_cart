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

class CreateCourierCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:create-courier';

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
            ->setDescription('Creates a courier account')
            ->setHelp('This command allows you to create a new courier account...')
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'The username of the courier account',
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'The password of the courier account',
            )
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $output->writeln('Starting the creation of a new courier account...');

        $userRepository = $this->entityManager
            ->getRepository(User::class)
        ;

        $user = $userRepository->findBy(['email' => $input->getArgument('email')]);

        if ($user) {
            $output->writeln('An account with this email already exists.');
            $output->writeln('Ending the creation of a new courier account.');
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
        $user->setRoles(['ROLE_COURIER']);
        $user->setVerified(true);

        $this->entityManager
            ->persist($user);
        $this->entityManager
            ->flush();

        $output->writeln('The courier account for ' . $input->getArgument('email') . ' has been created.');
        $output->writeln('Ending the creation of a new courier account.');
        return Command::SUCCESS;
    }
}
