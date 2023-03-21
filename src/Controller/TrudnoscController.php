<?php
/**
 * Trudnosc controller.
 */

namespace App\Controller;

use App\Entity\Trudnosc;
use App\Form\HomeType;
use App\Form\TrudnoscType;
use App\Repository\TrasaRepository;
use App\Service\TrasaService;
use App\Service\TrudnoscService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrudnoscController.
 *
 * @Route("/trudnosc")
 *
 */
class TrudnoscController extends AbstractController
{

    /**
     * Trudnosc service.
     *
     * @var TrudnoscService
     */
    private $trudnoscService;

    /**
     * TrudnoscController constructor.
     *
     * @param TrudnoscService $trudnoscService Trudnosc service
     */
    public function __construct(TrudnoscService $trudnoscService)
    {
        $this->trudnoscService = $trudnoscService;
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
     *     name="trudnosc_index",
     * )
     */
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        $page = $request->query->getInt('page', 1);
        $pagination = $this->trudnoscService->createPaginatedList($page);

        return $this->render(
            'trudnosc/index.html.twig',
            ['pagination' => $pagination,
                'searchForm' => $searchForm->createView(),
                ]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Trudnosc $trudnosc Trudnosc entity
     * @param TrasaRepository $trasaRepository
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param TrasaService $trasaService
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="trudnosc_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Trudnosc $trudnosc, Request $request, TrasaRepository $trasaRepository, TrasaService $trasaService): Response
    {

        $page = $request->query->getInt('page', 1);
        $pagination = $trasaService->createPaginatedList($page);

        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        return $this->render(
            'trudnosc/show.html.twig',
            ['trudnosc' => $trudnosc,
                'searchForm' => $searchForm->createView(),
                'trasy' => $trasaRepository->findBy(['trudnosc' => $trudnosc]),
                'pagination' => $pagination,
            ]
        );
    }
}