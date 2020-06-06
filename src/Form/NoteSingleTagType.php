<?php
/**
 * NoteSingleTag type.
 */

namespace App\Form;

use App\Entity\NoteTag;
use App\Form\DataTransformer\NoteTagsDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NoteSingleTagType.
 */
class NoteSingleTagType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var \App\Form\DataTransformer\NoteTagsDataTransformer
     */
    private $tagsDataTransformer;

    /**
     * TagType constructor.
     *
     * @param \App\Form\DataTransformer\NoteTagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(NoteTagsDataTransformer $tagsDataTransformer)
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
                'label' => 'label_name',
                'required' => true,
                'attr' => ['max_length' => 100],
            ]
        );
        /*$builder->get('name')->addModelTransformer(
            $this->tagsDataTransformer
        );*/
    }

    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => NoteTag::class]);
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
        return 'noteTag';
    }
}