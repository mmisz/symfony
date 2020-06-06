<?php
/**
 * Tags data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\ListTag;
use App\Repository\ListTagRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ListTagsDataTransformer.
 */
class ListTagsDataTransformer implements DataTransformerInterface
{
    /**
     * ListTag repository.
     *
     * @var \App\Repository\ListTagRepository
     */
    private $repository;

    /**
     * ListTagsDataTransformer constructor.
     *
     * @param \App\Repository\ListTagRepository $repository NoteTag repository
     */
    public function __construct(ListTagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transform array of tags to string of names.
     *
     * @param \Doctrine\Common\Collections\Collection $listTags ListTag entity collection
     *
     * @return string Result
     */
    public function transform($listTags): string
    {
        if (null == $listTags) {
            return '';
        }

        $tagNames = [];

        foreach ($listTags as $tag) {
            $tagNames[] = $tag->getName();
        }

        return implode(',', $tagNames);
    }

    /**
     * Transform string of tag names into array of ListTag entities.
     *
     * @param string $value String of listTag names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): array
    {
        $tagNames = explode(',', $value);

        $listTags = [];

        foreach ($tagNames as $tagName) {
            if ('' !== trim($tagName)) {
                $listTag = $this->repository->findOneByName(strtolower($tagName));
                if (null == $listTag) {
                    $listTag = new ListTag();
                    $listTag->setName($tagName);
                    $this->repository->save($listTag);
                }
                $listTags[] = $listTag;
            }
        }

        return $listTags;
    }
}
