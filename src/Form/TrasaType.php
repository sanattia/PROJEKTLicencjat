<?php
/**
 * Trasa type.
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
 * Class TrasaType.
 */
class TrasaType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var \App\Form\DataTransformer\TagsDataTransformer
     */
    private $tagsDataTransformer;

    /**
     * TrasaType constructor.
     *
     * @param \App\Form\DataTransformer\TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

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
                'label' => 'Nazwa Trasy',
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
            'adres',
            CKEditorType::class,
            [
                'label' => 'Artykuł',
                'input_sync' => true,
                'required' => false,
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

        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'Tagi',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );

        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'Tagi',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );


        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
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
