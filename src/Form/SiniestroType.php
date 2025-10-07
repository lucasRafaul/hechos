<?php
namespace App\Form;

use App\Entity\Siniestro;
use App\Entity\Clima;
use App\Entity\Localidad;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, DateTimeType, TextareaType};
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiniestroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', DateTimeType::class, ['widget' => 'single_text'])
            ->add('clima', EntityType::class, [
                'class' => Clima::class,
                'choice_label' => 'descripcion'
            ])
            ->add('localidad', EntityType::class, [
                'class' => Localidad::class,
                'choice_label' => 'nombre'
            ])
            ->add('ubicacion', TextType::class)
            ->add('calle', TextType::class)
            ->add('altura', TextType::class)
            ->add('descripcion', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Siniestro::class]);
    }
}
