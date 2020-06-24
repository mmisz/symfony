<?php

namespace App\Form;

// src/Form/UserPasswordType.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserPasswordType
 * @package App\Form
 */
class UserPasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'old_password',
            PasswordType::class,
            [
                'mapped' => false,
                'label' => 'old_password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                ],
            ]
        );
        $builder
            ->add('new_password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat_Password'),
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}

