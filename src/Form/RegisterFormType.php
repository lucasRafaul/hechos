<?php
namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, PasswordType, ChoiceType, SubmitType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rol',
                'choices' => [
                    'OPERADOR' => 'ROLE_OPERADOR',
                    'ADMIN' => 'ROLE_ADMIN'
                ],
                'multiple' => true, // puede tener varios roles
                'expanded' => true  // muestra como checkboxes
            ])
            ->add('registrar', SubmitType::class, [
                'label' => 'Registrar usuario',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
