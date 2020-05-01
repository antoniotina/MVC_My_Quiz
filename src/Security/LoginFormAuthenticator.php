<?php
namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;


class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $userRepository;
    private $router;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'login'
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        return [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        return $this->userRepository->findOneBy(['username' => $credentials['username']]);
    }
    
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $user = $this->userRepository->findOneBy(['username' => $request->request->get('_username')]);
        $user->setLast_connection(new \DateTime());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        if ($user->getValidated_at() === null) {
            return new RedirectResponse($this->router->generate('app_logout'));
        }
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    public function supportsRentityManagerentityManagerberMe()
    {
    }

    protected function getLoginUrl()
    {
    }
}