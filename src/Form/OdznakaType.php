<?php
/**
 * Trasa type.
 */

namespace App\Form;

use App\Entity\Odznaka;
use App\Entity\User;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

/**
 * Class OdznakaType.
 */
class OdznakaType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var \App\Form\DataTransformer\TagsDataTransformer
     */
    private $odznakiDataTransformer;

    /**
     * OdznakaType constructor.
     *
     * @param \App\Form\DataTransformer\OdznakiDataTransformer $odznakiDataTransformer Odznaki data transformer
     */
    public function __construct(TagsDataTransformer $odznakiDataTransformer)
    {
        $this->odznakiDataTransformer = $odznakiDataTransformer;
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
            'users',
            EntityType::class,
            [
                'class' => User::class,
                'choice_label' => function ($user): string {
                    return $user->getUsername();
                },
                'label' => 'odznaki',
                'placeholder' => 'label.none',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'by_reference' => false,
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
        $resolver->setDefaults(['data_class' => Odznaka::class]);
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
        return 'user_odznaka';
    }
}
