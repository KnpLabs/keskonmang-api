<?php

namespace App\Symfony\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_token', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\NotNull(),                    
                ]
            ])
        ;
    }
}