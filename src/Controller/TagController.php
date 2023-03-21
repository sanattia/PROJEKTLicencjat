<?php
/**
 * Tag controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\HomeType;
use App\Repository\TrasaRepository;
use App\Service\TagService;
use App\Service\TrasaService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TagController.
 *
 * @Route("/tag")
 *
 * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
 */
class TagController extends AbstractController
{
    /**
     * Tag service.
     *
     * @var TagService
     */
    private $tagService;

    /**
     * Trasa service.
     *
     * @var TrasaService
     */
    private $trasaService;

    /**
     * TagController constructor.
     *
     * @param TagService $tagService Tag service
     * @param TrasaService $trasaService Trasa service
     */
    public function __construct(TagService $tagService, TrasaService $trasaService)
    {
        $this->tagService = $tagService;
        $this->trasaService = $trasaService;
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
     *     name="tag_index",
     * )
     */
    public function index(Request $request): Response
    {

        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $pagination = $this->tagService->createPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render(
            'tag/index.html.twig',
            ['pagination' => $pagination,
                'searchForm' => $searchForm->createView(),]
        );
    }



    /**
     * Show action.
     *
     * @param Tag           $tag
     *
     * @param TrasaRepository $trasaRepository
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param TrasaService $trasaService
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="tag_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Tag $tag, Request $request, TrasaRepository $trasaRepository, TrasaService $trasaService): Response
    {
        $page = $request->query->getInt('page', 1);


        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $dane = $tag->getId();
        
        $filters = [];
        $filters['tag_id'] = $dane;

        $pagination = $this->trasaService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );
        return $this->render(
            'tag/show.html.twig',
            [
                'tag' => $tag,
                'searchForm' => $searchForm->createView(),
                'trasy' => $trasaRepository->queryAll($filters),
                'pagination' => $pagination,
            ]
        );
    }

}

