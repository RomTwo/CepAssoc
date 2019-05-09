<?php

namespace App\Security;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class LoginApiAuthentificator extends AbstractGuardAuthenticator
{

    private $entityManager;
    private $passwordEncoder;


    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return JsonResponse::create("Authentification is required", 401);
    }

    public function supports(Request $request)
    {
        return 'connexion_api' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $content = json_decode($request->getContent());
        $credentials = array(
            'email' => $content->email,
            'password' => $content->password,
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $this->entityManager->getRepository(Account::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('the identifiers are incorrect');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        );
        return JsonResponse::create($data, Response::HTTP_FORBIDDEN);
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->handleAuthenticationSuccess($token->getUser());

    }

    public function handleAuthenticationSuccess(UserInterface $user)
    {
        $payload = array(
            'username' => $user->getUsername(),
            'iat' => time(),
            'exp' => time() + 3600
        );
        $token = JWT::encode($payload, $_ENV['PRIVATE_KEY'], $_ENV['ALG']);

        $user = $this->entityManager->getRepository(Account::class)->findOneBy(['email' => $user->getUsername()]);
        $user->setTokenPlugin($token);
        $this->entityManager->flush();

        return JsonResponse::create(array('token' => $token), Response::HTTP_ACCEPTED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}