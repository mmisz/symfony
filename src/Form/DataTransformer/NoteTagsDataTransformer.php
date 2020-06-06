<?php
/**
 * Tags data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\NoteTag;
use App\Repository\NoteTagRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class NoteTagsDataTransformer.
 */
class NoteTagsDataTransformer implements DataTransformerInterface
{
    /**
     * NoteTag repository.
     *
     * @var \App\Repository\NoteTagRepository
     */
    private $repository;

    /**
     * NoteTagsDataTransformer constructor.
     *
     * @param \App\Repository\NoteTagRepository $repository NoteTag repository
     */
    public function __construct(NoteTagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transform array of tags to string of names.
     *
     * @param \Doctrine\Common\Collections\Collection $noteTags NoteTag entity collection
     *
     * @return string Result
     */
    public function transform($noteTags): string
    {
        if (null == $noteTags) {
            return '';
        }

        $tagNames = [];

        foreach ($noteTags as $tag) {
            $tagNames[] = $tag->getName();
        }

        return implode(',', $tagNames);
    }

    /**
     * Transform string of tag names into array of NoteTag entities.
     *
     * @param string $value String of noteTag names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): array
    {
        $tagNames = explode(',', $value);

        $noteTags = [];

        foreach ($tagNames as $tagName) {
            if ('' !== trim($tagName)) {
                $noteTag = $this->repository->findOneByName(strtolower($tagName));
                if (null == $noteTag) {
                    $noteTag = new NoteTag();
                    $noteTag->setName($noteTag);
                    $this->repository->save($noteTag);
                }
                $noteTags[] = $noteTag;
            }
        }

        return $noteTags;
    }
}
