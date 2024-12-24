<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Créer un compte administrateur',
)]
class CreateAdminCommand extends Command
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $em;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Créer un compte administrateur')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email de l\'administrateur')
            ->addArgument('username', InputArgument::OPTIONAL, 'Nom d\'utilisateur de l\'administrateur')
            ->addArgument('password', InputArgument::OPTIONAL, 'Mot de passe de l\'administrateur')
            ->addArgument("confirmPassword",InputArgument::OPTIONAL,"Confirmation mot de passe de l\'administrateur");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Get input arguments
        $email = $input->getArgument('email');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $confirmPassword = $input->getArgument("confirmPassword");

        // Interactive fallback for missing arguments
        if (!$email) {
            $email = $io->ask('Veuillez saisir une adresse email', null, function ($value) {
                if (empty($value)) {
                    throw new \RuntimeException('L\'adresse email est obligatoire.');
                }
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new \RuntimeException('Adresse email invalide.');
                }
                return $value;
            });
        }

        if (!$username) {
            $username = $io->ask('Veuillez saisir un nom d\'utilisateur', null, function ($value) {
                if (empty($value)) {
                    throw new \RuntimeException('Le nom d\'utilisateur est obligatoire.');
                }
                return $value;
            });
        }

        if (!$password) {
            $password = $io->askHidden('Veuillez saisir un mot de passe', function ($value) {
                if (empty($value)) {
                    throw new \RuntimeException('Le mot de passe est obligatoire.');
                }
                if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $value)) {
                    throw new \RuntimeException('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.');
                }
                return $value;
            });
        }

        if(!$confirmPassword){
           $confirmPassword = $io->askHidden('Veuillez confirmer le mot de passe', function ($value) use ($password) {
                if ($value !== $password) {
                    throw new \RuntimeException('Les mots de passe ne correspondent pas.');
                }
                return $value;
            }); 
        }
        

        // Create and persist the admin user
        $user = new User();
        $user->setEmail($email)
            ->setUsername($username)
            ->setPassword($this->passwordHasher->hashPassword($user, $password))
            ->setRoles(['ROLE_ADMINISTRATEUR']);

        $this->em->persist($user);
        $this->em->flush();

        $io->success("Le compte administrateur '$username' a été créé avec succès !");
        return Command::SUCCESS;
    }
}
