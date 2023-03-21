<?php
/**
 * Region controller.
 */

namespace App\Controller;

use App\Entity\Region;
use App\Form\HomeType;
use App\Form\RegionType;
use App\Repository\TrasaRepository;
use App\Service\RegionService;
use App\Service\TrasaService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RegionController.
 *
 * @Route("/region")
 *
 */
class RegionController extends AbstractController
{

    /**
     * Region service.
     *
     * @var RegionService
     */
    private RegionService $regionService;

    /**
     * RegionController constructor.
     *
     * @param RegionService $regionService Region service
     */
    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="region_index",
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->regionService->createPaginatedList($page);
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        return $this->render(
            'region/index.html.twig',
            ['pagination' => $pagination,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Region $region Region entity
     * @param TrasaRepository $trasaRepository
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param TrasaService $trasaService
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="region_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Region $region, TrasaRepository $trasaRepository,Request $request, TrasaService $trasaService): Response
    {

        $page = $request->query->getInt('page', 1);
        $pagination = $trasaService->createPaginatedList($page);
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        return $this->render(
            'region/show.html.twig',
            ['region' => $region,
                'trasy' => $trasaRepository->findBy(['region' => $region]),
                'pagination' => $pagination,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }
}