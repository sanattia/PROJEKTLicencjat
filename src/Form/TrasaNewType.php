<?php
/**
 * TrasaNew type.
 */

namespace App\Form;

use App\Entity\Trasa;
use App\Entity\Trudnosc;
use App\Entity\Region;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

/**
 * Class TrasaNewType.
 */
class TrasaNewType extends AbstractType
{

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array                                        $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'Nazwa',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );

        $builder->add('imageFile', VichImageType::class, [
            'required' => false,
            'allow_delete' => true,
            'delete_label' => 'Usuń',
            'download_label' => 'Pobierz',
            'download_uri' => true,
            'image_uri' => true,
            'asset_helper' => true,
        ]);

        $builder->add(
            'trudnosc',
            EntityType::class,
            [
                'class' => Trudnosc::class,
                'choice_label' => function ($trudnosc) {
                    return $trudnosc->getName();
                },
                'label' => 'Trudność',
                'placeholder' => 'label_none',
                'required' => true,
            ]
        );
        $builder->add(
            'region',
            EntityType::class,
            [
                'class' => Region::class,
                'choice_label' => function ($region) {
                    return $region->getName();
                },
                'label' => 'Region',
                'placeholder' => 'label_none',
                'required' => true,
            ]
        );

        $builder->add(
            'PunktStartowy',
            TextType::class,
            [
                'label' => 'Punkt Startowy',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );
        $builder->add(
            'PunktKoncowy',
            TextType::class,
            [
                'label' => 'Punkt Koncowy',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );

        $builder->add(
            'points',
            NumberType::class,
            [
                'label' => 'Punkty',
                'required' => true,
            ]
        );

        $builder->add(
            'czas',
            TimeType::class,
            [
                'label' => 'Czas przejścia',
                'input'  => 'datetime',
                'widget' => 'choice',
            ]
        );

    }

    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Trasa::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'trasa';
    }
}
