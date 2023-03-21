<?php
/**
 * Wpis type.
 */

namespace App\Form;

use App\Entity\Wpis;
use App\Entity\Trasa;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class WpisType.
 */
class WpisType extends AbstractType
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
            'trasa',
            EntityType::class,
            [
                'class' => Trasa::class,
                'choice_label' => function ($trasa) {
                    return $trasa->getName();
                },
                'label' => 'Trasa',
                'placeholder' => 'puste',
                'required' => true,
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
    }

    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Wpis::class]);
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
        return 'wpis';
    }
}
