<?php
/**
 * Login form authenticator.
 */

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class LoginFormAuthenticator.
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    /**
     * User service.
     *
     * @var UserService
     */
    private UserService $userService;

    /**
     * URL generator.
     *
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * CSRF token manager.
     *
     * @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * Password encoder.
     *
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * LoginFormAuthenticator constructor.
     *
     * @param \App\Service\UserService                                              $userService      User service
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface            $urlGenerator     URL generator
     * @param \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface            $csrfTokenManager CSRF token manager
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder  Password encoder
     */
    public function __construct(UserService $userService, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userService = $userService;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Does the authenticator support the given Request?
     *
     * If this returns false, the authenticator will be skipped.
     *
     * @param Request $request HTTP request
     *
     * @return bool Result
     */
    public function supports(Request $request): bool
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * Get the authentication credentials from the request and return them
     * as any type (e.g. an associate array). If you return null, authentication
     * will be skipped.
     *
     * @param Request $request HTTP request
     *
     * @return array Credential array
     */
    public function getCredentials(Request $request): array
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
     * Get user.
     *
     * @param mixed                                                       $credentials  Credentials
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider User provider
     *
     * @return \App\Entity\User|null Result
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?User
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->userService->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    /**
     * Checks credentials.
     *
     * @param mixed                                               $credentials Credentials
     * @param \Symfony\Component\Security\Core\User\UserInterface $user        User
     *
     * @return bool Result
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param array $credentials User credentials
     *
     * @return string|null Hashed password
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    /**
     * Called when authentication executed and was successful!
     *
     * @param \Symfony\Component\HttpFoundation\Request                            $request     HTTP request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token       Authentication token
     * @param string                                                               $providerKey The key of the firewall
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Redirect response
     *
     * @throws \Exception
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    /**
     * Return the URL to the login page.
     *
     * @return string Login URL
     */
    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('app_login');
    }
}
