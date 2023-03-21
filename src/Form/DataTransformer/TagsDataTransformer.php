<?php
/**
 * Tags data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Service\TagService;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 */
class TagsDataTransformer implements DataTransformerInterface
{
    /**
     * Tag service.
     *
     * @var TagService
     */
    private $tagService;

    /**
     * TagsDataTransformer constructor.
     *
     * @param TagService $tagService Tag service
     */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Transform array of tags to string of names.
     *
     * @param \Doctrine\Common\Collections\Collection $tags Tags entity collection
     *
     * @return string Result
     */
    public function transform($tags): string
    {
        if (null == $tags) {
            return '';
        }

        $tagNames = [];

        foreach ($tags as $tag) {
            $tagNames[] = $tag->getTitle();
        }

        return implode(',', $tagNames);
    }

    /**
     * Transform string of tag names into array of Tag entities.
     *
     * @param string $value String of tag names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): array
    {
        $tagTitles = explode(',', $value);

        $tags = [];

        foreach ($tagTitles as $tagTitle) {
            if ('' !== trim($tagTitle)) {
                $tag = $this->tagService->findOneByTitle(strtolower($tagTitle));
                if (null == $tag) {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);
                    $this->tagService->save($tag);
                }
                $tags[] = $tag;
            }
        }

        return $tags;
    }
}
