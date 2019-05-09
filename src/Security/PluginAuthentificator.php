<?php

namespace App\Security;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class PluginAuthentificator extends AbstractGuardAuthenticator
{

    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array('message' => 'Authentification is required');
        return JsonResponse::create($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }


    public function getCredentials(Request $request)
    {
        if (!$token = $request->headers->get('Authorization')) {
            $token = null;
        }

        return array(
            'token' => $token
        );
    }


    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiKey = $credentials['token'];

        if (null === $apiKey) {
            return;
        }

        return $this->entityManager->getRepository(Account::class)->findOneBy(['tokenPlugin' => $apiKey]);
    }


    public function checkCredentials($credentials, UserInterface $user)
    {
        $encodeToken = $credentials['token'];
        try {
            JWT::decode($encodeToken, $_ENV['PRIVATE_KEY'], array($_ENV['ALG']));
            return true;
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException('Token is expired');
        }

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

    }

    public function supportsRememberMe()
    {
        return false;
    }
}