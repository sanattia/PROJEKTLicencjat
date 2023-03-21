<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\HomeType;
use App\Form\OdznakaType;
use App\Service\OdznakaService;
use App\Service\UserService;
use App\Service\WpisService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 *
 * @Route("/user")
 *
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * Wpis service.
     *
     * @var WpisService
     */
    private $wpisService;

    /**
     * Odznaka service.
     *
     * @var OdznakaService
     */
    private $userService;

    /**
     * WpisController constructor.
     *
     * @param WpisService $wpisService Wpis service
     * @param UserService $userService User service
     */
    public function __construct(WpisService $wpisService, UserService $userService)
    {
        $this->wpisService = $wpisService;
        $this->userService = $userService;
    }




    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="user_index",
     * )
     *  @IsGranted("ROLE_USER")
     */
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $pagination = $this->wpisService->getPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser()
        );

        return $this->render('user/index.html.twig', ['pagination' => $pagination, 'searchForm' => $searchForm->createView(),]);
    }

    /**
     * List action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/list",
     *     methods={"GET"},
     *     name="user_list",
     * )
     *
     *  @IsGranted("ROLE_ADMIN")
     */
    public function list(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->createPaginatedList($page);
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        return $this->render(
            'user/list.html.twig',
            ['pagination' => $pagination,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * Index action.
     * @param User  $user
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/list/{id}",
     *     methods={"GET"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_list_wpis",
     * )
     */
    public function listWpis(User $user, Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $pagination = $this->wpisService->getPaginatedList(
            $request->query->getInt('page', 1),
            $user
        );

        return $this->render('user/wpis.html.twig', ['pagination' => $pagination, 'searchForm' => $searchForm->createView()]);
    }


}
