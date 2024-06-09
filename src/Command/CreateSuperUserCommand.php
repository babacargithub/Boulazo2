<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateSuperUserCommand extends Command
{
    protected static $defaultName = 'app:create-super-user';

    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure()
    {
        $this
            ->setDescription('Créer un nouvel utilisateur')
            ->addArgument('prenom', InputArgument::OPTIONAL, 'The first name of the super user')
            ->addArgument('nom', InputArgument::OPTIONAL, 'The last name of the super user')
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the super user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password of the super user')
            ->addArgument('repeated-password', InputArgument::OPTIONAL, 'The repeated password of the super user')
            ->addOption('roles', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The roles of the super user', ['ROLE_SUPER_ADMIN']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $prenom = $input->getArgument('prenom');
        if (!$prenom) {
            $question = new Question('Préciser le prénom: ');
            $prenom = $helper->ask($input, $output, $question);
        }

        $nom = $input->getArgument('nom');
        if (!$nom) {
            $question = new Question('Préciser le nom: ');
            $nom = $helper->ask($input, $output, $question);
        }

        $username = $input->getArgument('username');
        if (!$username) {
            $question = new Question('Choisir le nom d\'utilisateur:');
            $username = $helper->ask($input, $output, $question);
        }

        $password = $input->getArgument('password');
        if (!$password) {
            $question = new Question('Mot de passe ? ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $password = $helper->ask($input, $output, $question);
        }
        $repeatedPassword = $input->getArgument('repeated-password');
        if (!$repeatedPassword) {
            $question = new Question('Répétez le Mot de passe ? ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $repeatedPassword = $helper->ask($input, $output, $question);
        }
        if ($password !== $repeatedPassword) {
            $output->writeln('Les mots de passe ne correspondent pas');
            return Command::FAILURE;
        }



        $user = new User();
        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setUsername($username);
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Utilisateur créé avec succès');

        return Command::SUCCESS;
    }
}
