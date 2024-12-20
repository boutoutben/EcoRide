<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:createAdmin',
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
        ->setDescription("Créer un compte administrateur")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user = new User();
        $helper = $this->getHelper('question');

        while (true) {
            $askEmail = new Question("Veuillez mettre une adresse email: ");
            $email = $helper->ask($input, $output, $askEmail);

            // Check if the email is empty
            if (empty($email)) {
                $output->writeln("<error>Le champ ne peut pas être vide</error>");
                continue; // Restart the loop
            }

            // Define the regex pattern for email validation
            $patternEmail = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";

            // Validate the email format
            if (!preg_match($patternEmail, $email)) {
                $output->writeln("<error>Le mail n'est pas conforme</error>");
                continue; // Restart the loop
            }

            // If email passes validation, break out of the loop
            break;
        }

        while (true) {
            $askUsername = new Question('Veuillez mettre un username: ');
            $username = $helper->ask($input, $output, $askUsername);

            // Check if the email is empty
            if (empty($username)) {
                $output->writeln("<error>Le champ ne peut pas être vide</error>");
                continue; // Restart the loop
            }

            $parternUsername = "/^[A-Za-z0-9&'’.\- ]{2,50}$/";

            if (!preg_match($parternUsername, $username)) {
                $output->writeln("<error>L'username n'est pas conforme</error>");
                continue; // Restart the loop
            }

            // If email passes validation, break out of the loop
            break;
        }

        while (true) {
            $askPassword = new Question("Veuillez saisir votre mot de passe: ");
            $askPassword->setHidden(true)
                        ->setHiddenFallback(false);
            $password = $helper->ask($input, $output, $askPassword);

            // Check if the email is empty
            if (empty($password)) {
                $output->writeln("<error>Le champ ne peut pas être vide</error>");
                continue; // Restart the loop
            }

            $parternPassword = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
            if (!preg_match($parternPassword, $password)) {
                $output->writeln("<error>Le mot de passe n'est pas conforme</error>");
                continue; // Restart the loop
            }

            // If email passes validation, break out of the loop
            break;
        }

        while (true) {
            $askPasswordAgain = new Question("Veuillez confirmer votre mot de passe: ");
            $askPasswordAgain->setHidden(true)
                ->setHiddenFallback(false);
            $passwordAgain = $helper->ask($input, $output, $askPasswordAgain);
            if ($passwordAgain) {
                while($password != $passwordAgain)
                {
                    $output->writeln("Les deux mot de passe ne sont pas égaux");
                    $askPasswordAgain = new Question("Veuillez confirmer votre mot de passe: ");
                    $passwordAgain = $helper->ask($input, $output, $askPasswordAgain);
                }
                break; 
                
            }
            $output->writeln("Le champ ne peut pas être vide");
        }

        $user->setEmail($email)
             ->setUsername($username)
             ->setPassword($this->passwordHasher->hashPassword($user,$password))
             ->setRoles(["ROLE_ADMINISTRATEUR"]);
        $this->em->persist($user);
        $this->em->flush();
        return Command::SUCCESS;
    }
}
