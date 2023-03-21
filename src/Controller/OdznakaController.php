<?php
/**
 * Odznaka controller.
 */

namespace App\Controller;

use App\Entity\Odznaka;
use App\Form\HomeType;
use App\Form\OdznakaType;
use App\Repository\UserRepository;
use App\Service\OdznakaService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OdznakaController.
 *
 * @Route("/odznaka")
 *
 * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
 */
class OdznakaController extends AbstractController
{
    /**
     * Odznaka service.
     *
     * @var OdznakaService
     */
    private $odznakaService;

    /**
     * User service.
     *
     * @var UserService
     */
    private $userService;

    /**
     * OdznakaController constructor.
     *
     * @param OdznakaService $odznakaService Odznaka service
     * @param UserService $userService User service
     */
    public function __construct(OdznakaService $odznakaService, UserService $userService)
    {
        $this->odznakaService = $odznakaService;
        $this->userService = $userService;
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
     *     name="odznaka_index",
     * )
     * @IsGranted("ROLE_ADMIN")
     *
     */
    public function index(Request $request): Response
    {

        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $pagination = $this->odznakaService->createPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render(
            'odznaka/index.html.twig',
            ['pagination' => $pagination,
                'searchForm' => $searchForm->createView(),]
        );
    }



    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Odznaka                          $odznaka    Odznaka entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="odznaka_edit",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Odznaka $odznaka): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        $form = $this->createForm(OdznakaType::class, $odznaka, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->odznakaService->save($odznaka);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('odznaka_index');
        }

        return $this->render(
            'odznaka/edit.html.twig',
            [
                'form' => $form->createView(),
                'odznaka' => $odznaka,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

}

