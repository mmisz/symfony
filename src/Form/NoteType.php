<?php
/**
 * Note type.
 */

namespace App\Form;

use App\Entity\Note;
use App\Entity\NoteCategory;
use App\Form\DataTransformer\NoteTagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NoteType.
 */
class NoteType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var \App\Form\DataTransformer\NoteTagsDataTransformer
     */
    private $tagsDataTransformer;

    /**
     * TaskType constructor.
     *
     * @param \App\Form\DataTransformer\NoteTagsDataTransformer $noteDataTransformer Tags data transformer
     */
    public function __construct(NoteTagsDataTransformer $noteDataTransformer)
    {
        $this->tagsDataTransformer = $noteDataTransformer;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder The form builder
     * @param array $options The options
     * @see FormTypeExtensionInterface::buildForm()
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'label_title',
                'required' => true,
                'attr' => ['max_length' => 100],
            ]
        );
        $builder->add('category', EntityType::class, [
            // looks for choices from this entity
            'class' => NoteCategory::class,

            // uses the User.username property as the visible option string
            'choice_label' => 'title',

            // used to render a select box, check boxes or radios
            // 'multiple' => true,
            // 'expanded' => true,
        ]);
        $builder->add(
            'noteTags',
            TextType::class,
            [
                'label' => 'label_tags',
                'required' => false,
                'attr' => ['max_length' => 100],
            ]
        );
        $builder->add(
            'content',
            TextareaType::class,
            [
                'label' => 'label_content',
                'required' => true,
            ]
        );
        $builder->get('noteTags')->addModelTransformer(
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
        $resolver->setDefaults(['data_class' => Note::class]);
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
        return 'Note';
    }
}