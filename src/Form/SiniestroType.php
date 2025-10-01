<?php
namespace App\Form;

use App\Entity\Siniestro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, DateTimeType, TextareaType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiniestroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', DateTimeType::class, ['widget' => 'single_text'])
            ->add('localidad', TextType::class)
            ->add('calle', TextType::class)
            ->add('coordenadas', TextType::class, ['required' => false])
            ->add('descripcion', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Siniestro::class]);
    }
}
