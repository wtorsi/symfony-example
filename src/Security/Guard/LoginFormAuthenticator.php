<?php

declare(strict_types=1);

namespace Security\Guard;

use Security\Exception\CustomUserMessageAuthenticationException;
use Security\Exception\FormAuthenticationException;
use Security\Form\Dto\LoginDto;
use Security\Form\Type\LoginType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;
    use \FormTrait;

    private RouterInterface $router;
    private UserPasswordEncoderInterface $passwordEncoder;
    private FormFactoryInterface $factory;
    private TranslatorInterface $translator;

    public function __construct(FormFactoryInterface $factory, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->factory = $factory;
        $this->translator = $translator;
    }

    public function supports(Request $request)
    {
        return 'security_check' === $request->attributes->get('_route') && $request->isMethod('POST') && $request->isXmlHttpRequest();
    }

    public function getCredentials(Request $request)
    {
        $dto = new LoginDto();
        $form = $this->factory->create(LoginType::class, $dto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            throw new CustomUserMessageAuthenticationException('security.invalid_credentials');
        }

        if (!$form->isValid()) {
            $errors = $this->serializeFormErrors($form);

            throw new FormAuthenticationException($errors);
        }

        $credentials = [
            'username' => $dto->username,
            'password' => $dto->password,
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $user = $userProvider->loadUserByUsername($credentials['username']);
        } catch (UsernameNotFoundException $e) {
            throw new CustomUserMessageAuthenticationException('security.user_not_found');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): Response
    {
        if (!$targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            $targetPath = $this->router->generate('page_index');
        }

        return new JsonResponse([
            'success' => true,
            'location' => $targetPath,
        ], 302);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            if ($exception instanceof FormAuthenticationException) {
                return new JsonResponse([
                    'errors' => $exception->getErrors(),
                ], 400);
            }

            // not form exception

            $form = $this->factory->create(LoginType::class);
            $form->get('username')->addError(new FormError(
                $this->translator->trans($exception->getMessageKey(), $exception->getMessageData(), 'validators')
            ));

            $errors = $this->serializeFormErrors($form);

            return new JsonResponse([
                'errors' => $errors,
            ], 400);
        }

        return parent::onAuthenticationFailure($request, $exception);
    }

    protected function getLoginUrl(): string
    {
        return $this->router->generate('security_login');
    }
}
