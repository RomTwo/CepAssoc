<?php

namespace App\Security;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginFormAuthentificator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * Access to database
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Permit to generate an url for redirect the current user
     *
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * Permit to generate the token and for check if the ever the same user
     *
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * Crypt or decrypt the user password
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * LoginFormAuthentificator constructor.
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Check if the current correspond at 'security_connexion' route and if the type of request is POST
     *
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return 'security_connexion' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * Get and store in array the parameters of the request
     *
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * Get the user from the database with your email address (present in array create by the function getCredentials)
     *
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return null|object
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('Une erreur s\'est produit');
        }

        $user = $this->entityManager->getRepository(Account::class)->findOneBy(['email' => $credentials['email']]);
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Erreur : Les identifiants saisis sont incorrects');
        }

        return $user;
    }

    /**
     * Check if the user password is valid
     *
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            return true;
        }
        throw new CustomUserMessageAuthenticationException('Erreur : Les identifiants saisis sont incorrects');
    }

    /**
     * If already verification return true, the user is authentified and he's redirect at the homepage of the web site
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        $url = $this->urlGenerator->generate('home');
        return new RedirectResponse($url);
    }

    /**
     * If not already verification return false, the user is not authentified and he's redirect on the login form
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        $url = $this->urlGenerator->generate('security_connexion');
        return new RedirectResponse($url);
    }

    /**
     * Get the login form url
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('security_connexion');
    }
}
