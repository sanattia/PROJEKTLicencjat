<?php
/**
 * Odznaki data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Odznaka;
use App\Repository\OdznakaRepository;
use App\Service\OdznakaService;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class OdznakiDataTransformer.
 */
class OdznakiDataTransformer implements DataTransformerInterface
{
    /**
     * Odznaka service.
     *
     * @var OdznakaService
     */
    private $odznakaService;

    /**
     * OdznakiDataTransformer constructor.
     *
     * @param OdznakaService $odznakaService Odznaka service
     */
    public function __construct(OdznakaService $odznakaService)
    {
        $this->odznakaService = $odznakaService;
    }

    /**
     * Transform array of odznaki to string of names.
     *
     * @param \Doctrine\Common\Collections\Collection $odznaki Odznaki entity collection
     *
     * @return string Result
     */
    public function transform($odznaki): string
    {
        if (null == $odznaki) {
            return '';
        }

        $odznakaNames = [];

        foreach ($odznaki as $odznaka) {
            $odznakaNames[] = $odznaka->getTitle();
        }

        return implode(',', $odznakaNames);
    }

    /**
     * Transform string of odznaka names into array of Odznaka entities.
     *
     * @param string $value String of odznaka names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): array
    {
        $odznakaTitles = explode(',', $value);

        $odznaki = [];

        foreach ($odznakaTitles as $odznakaTitle) {
            if ('' !== trim($odznakaTitle)) {
                $odznaka = $this->odznakaService->findOneByTitle(strtolower($odznakaTitle));
                if (null == $odznaka) {
                    $odznaka = new Odznaka();
                    $odznaka->setTitle($odznakaTitle);
                    $this->odznakaService->save($odznaka);
                }
                $odznaki[] = $odznaka;
            }
        }

        return $odznaki;
    }
}
