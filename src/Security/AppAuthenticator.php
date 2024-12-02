<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AppAuthenticator extends AbstractLoginFormAuthenticator implements AuthenticationEntryPointInterface
{
    use TargetPathTrait;

    public function savePath(SessionInterface $session): void
    {
        $this->saveTargetPath($session, 'main', '/userSpace');
    }

    public const LOGIN_ROUTE = 'app_connexion';

    private UserRepository $userRepository;
    private RouterInterface $router;

    public function __construct(UrlGeneratorInterface $urlGenerator, UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function authenticate(Request $request): Passport
    {
        // Récupérez les données de la requête
        $username =  $request->request->all('connexion')['pseudo'];
        $password = $request->request->all('connexion')['password'];

        $usernamePattern = "/^[a-zA-Z0-9\s.,'!?():-]{3,50}$/";
        $passwordPartern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,50}$/";
        // Vérifiez si le username est manquant
        if (!preg_match($usernamePattern, $username)) {
            throw new CustomUserMessageAuthenticationException("Le nom d'utilisateur doit contenir en 3 et 50 et ne peux contenir uniquement certain caractère spécial (. , ! ? () : -)");
        }
        if(!preg_match($passwordPartern, $password)){
            throw new CustomUserMessageAuthenticationException("Le mot de passe doit contenir en 3 et 50 caractère and être constitué au moins d'une majuscule, d'une minuscule, d'un chiffre et d'un caractère spécial");
        }
        // Vérifiez si le password est manquant
        // Créez le Passport
        return new Passport(
            // UserBadge to load the user
            new UserBadge($username, function ($userIdentifier) {
                $user = $this->userRepository->findOneBy(['username' => $userIdentifier]);

                if (!$user) {
                    // Throw an exception if the user is not found
                    throw new UserNotFoundException('User not found.');
                }

                return $user;
            }),
            // PasswordCredentials to verify the password
            new PasswordCredentials($password),
            [
                
                new CsrfTokenBadge('authenticate', $request->request->get("_csrf_token")),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($target = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($target);
        }
        return new RedirectResponse(
            $this->router->generate('app_home')
        );
    }
    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_connexion');
    }
}
