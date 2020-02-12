<?php

declare(strict_types=1);

namespace Security\Form\Type;

use Security\Form\Dto\LoginDto;
use Security\Form\Transformer\LowercaseTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginType extends AbstractType
{
    private AuthenticationUtils $authenticationUtils;
    private TranslatorInterface $translator;

    public function __construct(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', EmailType::class, [
                'required' => true,
                'label' => 'security.field.username',
                'attr' => [
                    'autocomplete' => 'username email',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email(['mode' => 'strict']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'security.field.password',
                'attr' => [
                    'autocomplete' => 'current-password',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ]);

        $builder->get('username')->addViewTransformer(new LowercaseTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'post',
            'csrf_token_id' => 'authenticate',
            'data_class' => LoginDto::class,
        ]);
    }
}
