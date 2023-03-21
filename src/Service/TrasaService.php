<?php
/**
 * Trasa service.
 */

namespace App\Service;

use App\Entity\Trasa;
use App\Entity\Trudnosc;
use App\Entity\User;
use App\Repository\TrasaRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\TagService;
use App\Service\TrudnoscService;
use App\Service\RegionService;

/**
 * Class TrasaService.
 */
class TrasaService
{
    /**
     * Trasa repository.
     *
     * @var \App\Repository\TrasaRepository
     */
    private $trasaRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * Tag service.
     *
     * @var TagService
     */
    private $tagService;

    /**
     * Region service.
     *
     * @var RegionService
     */
    private $regionService;


    /**
     * Trudnosc service.
     *
     * @var TrudnoscService
     */
    private $trudnoscService;

    /**
     * TrasaService constructor.
     *
     * @param \App\Repository\TrasaRepository          $trasaRepository Trasa repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator      Paginator
     * @param RegionService $regionService Region service
     * @param TrudnoscService $trudnoscService Trudnosc service
     * @param TagService      $tagService      Tag Service
     */
    public function __construct(TrasaRepository $trasaRepository, PaginatorInterface $paginator, TagService $tagService, RegionService $regionService, TrudnoscService $trudnoscService)
    {
        $this->trasaRepository = $trasaRepository;
        $this->paginator = $paginator;
        $this->tagService = $tagService;
        $this->regionService = $regionService;
        $this->trudnoscService = $trudnoscService;
    }

    /**
     * Get paginated list.
     *
     * @param int  $page   Page number
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->trasaRepository->queryAllByAdmin($filters),
            $page,
            TrasaRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get mini paginated list.
     *
     * @param int  $page   Page number
     * @param array<string, int> $filters Filters array
     * @param User $author Author
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getMiniPaginatedList(int $page, array $filters = [], User $author): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->trasaRepository->queryAllByAuthor($filters, $author),
            $page,
            TrasaRepository::PAGINATOR_ITEMS_PER_PAGE_MINI
        );
    }

    /**
     * Get paginated list sorted.
     *
     * @param int $page Page number
     * @param array<string, int> $filters Filters array
     *
     * @param string $dane Dane
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedSearch(int $page, array $filters = [], string $dane): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->trasaRepository->querySearch($filters, $dane),
            $page,
            TrasaRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['region_id'])) {
            $region = $this->regionService->findOneById($filters['region_id']);
            if (null !== $region) {
                $resultFilters['region'] = $region;
            }
        }

        if (!empty($filters['trudnosc_id'])) {
            $trudnosc = $this->trudnoscService->findOneById($filters['trudnosc_id']);
            if (null !== $trudnosc) {
                $resultFilters['trudnosc'] = $trudnosc;
            }
        }

        if (!empty($filters['tag_id']) && is_numeric($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     * @param array<string, int> $filters Filters array
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);
        return $this->paginator->paginate(
            $this->trasaRepository->queryAllByAdmin($filters),
            $page,
            TrasaRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }



    /**
     * Save trasa.
     *
     * @param \App\Entity\Trasa $trasa Trasa entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Trasa $trasa): void
    {
        $this->trasaRepository->save($trasa);
    }

    /**
     * Delete trasa.
     *
     * @param \App\Entity\Trasa $trasa Trasa entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Trasa $trasa): void
    {
        $this->trasaRepository->delete($trasa);
    }

    /**
     * Find trasa by Id.
     *
     * @param int $id Trasa Id
     *
     * @return \App\Entity\Trasa|null Trasa entity
     */
    public function findOneById(int $id): ?Trasa
    {
        return $this->trasaRepository->findOneById($id);
    }

    /**
     * Find trasa by region
     * @param array $region
     *
     * @return Trasa[]
     */
    public function findBy(array $region): array
    {
        return $this->trasaRepository->findBy($region);
    }

    /**
     * Find trasa by trudnosc
     * @param array $trudnosc
     *
     * @return Trasa[]
     */
    public function findTrudnoscBy(array $trudnosc): array
    {
        return $this->trasaRepository->findBy($trudnosc);
    }

    /**
     * Find trasa by tag
     * @param array $tag
     *
     * @return Trasa[]
     */
    public function findTagBy(array $tag): array
    {
        return $this->trasaRepository->findBy($tag);
    }
}
