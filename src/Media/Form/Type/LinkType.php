<?php declare(strict_types=1);

namespace Media\Form\Type;

use Form\Validator\Constraints\Url;
use Media\Form\Dto\LinkDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LinkType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LinkDto::class,
            'label_format' => 'link.field.%name%',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', UrlType::class, [
                'required' => true,
                'default_protocol' => null,
                'constraints' => [
                    new NotBlank(),
                    new Url(),
                ],
            ])
            ->add('expirationDatetime', DateTimeType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'link.field.datetime',
                'help' => 'link.field.datetime_help',
                'constraints' => [
                    new Callback(function (?\DateTimeImmutable $value, ExecutionContextInterface $context) {
                        if (null === $value || $value > new \DateTimeImmutable()) {
                            return;
                        }

                        $context
                            ->buildViolation('link.expiration_less_than_now')
                            ->addViolation();
                    }),
                ],
            ]);
    }
}