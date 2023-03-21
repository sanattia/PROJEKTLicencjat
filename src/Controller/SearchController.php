<?php
/**
 * Search controller.
 */

namespace App\Controller;


use App\Form\HomeType;
use App\Service\TrasaService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController.
 *
 * @Route("/search")
 *
 * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
 */
class SearchController extends AbstractController
{
    /**
     * Trasa service.
     *
     * @var TrasaService
     */
    private $trasaService;

    /**
     * TrasaController constructor.
     *
     * @param TrasaService $trasaService Trasa service
     */
    public function __construct(TrasaService $trasaService)
    {
        $this->trasaService = $trasaService;
    }

    /**
     * Search action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"POST", "GET"},
     *     name="search_show"
     * )
     */
    public function index(Request $request): Response
    {

        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        if ($request->isMethod('post')) {
            $dane = $request->request->get('home')['search'];
        }

        else{
            return $this->render(
                'search/index.html.twig',
                ['searchForm' => $searchForm->createView(),]
            );
        }


        $filters = [];
        $filters['region_id'] = $request->query->getInt('filters_region_id');
        $filters['trudnosc_id'] = $request->query->getInt('filters_trudnosc_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        $pagination = $this->trasaService->getPaginatedSearch(
            $request->query->getInt('page', 1),
            $filters,
            $dane
        );

        return $this->render(
            'search/index.html.twig',
            [   'dane' => $dane,
                'pagination' => $pagination,
                'searchForm' => $searchForm->createView(),]
        );
    }



}
