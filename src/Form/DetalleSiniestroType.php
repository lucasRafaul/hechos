<?php
namespace App\Form;

use App\Entity\DetalleSiniestro;
use App\Entity\Siniestro;
use App\Entity\Persona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\{TextType, NumberType, TextareaType, ChoiceType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetalleSiniestroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_siniestro', EntityType::class, [
                'class' => Siniestro::class,
                'choice_label' => 'id'
            ])
            ->add('id_persona', EntityType::class, [
                'class' => Persona::class,
                'choice_label' => function($persona) { return $persona->getNombre().' '.$persona->getApellido(); }
            ])
            ->add('rol', ChoiceType::class, [
                'choices' => [
                    'Víctima' => 'víctima',
                    'Autor' => 'autor',
                    'Testigo' => 'testigo'
                ]
            ])
            ->add('estadoAlcoholico', TextType::class)
            ->add('porcentajeAlcohol', NumberType::class, ['required' => false])
            ->add('observaciones', TextareaType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => DetalleSiniestro::class]);
    }
}
