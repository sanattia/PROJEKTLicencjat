<?php
/**
 * Avatar controller.
 */

namespace App\Controller;

use App\Entity\Avatar;
use App\Form\HomeType;
use App\Form\AvatarType;
use App\Service\AvatarService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AvatarController.
 *
 * @Route("/avatar")
 *
 * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
 */
class AvatarController extends AbstractController
{
    /**
     * Avatar service.
     *
     * @var AvatarService
     */
    private $avatarService;

    /**
     * AvatarController constructor.
     *
     * @param AvatarService $avatarService Avatar service
     */
    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
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
     *     name="avatar_index",
     * )
     */
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $pagination = $this->avatarService->getPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser()
        );

        return $this->render('avatar/index.html.twig', ['pagination' => $pagination, 'searchForm' => $searchForm->createView()]);
    }
    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="avatar_create",
     * )
     *
     */
    public function create(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();
        $avatar = new Avatar();
        $avatar->setAuthor($user);
        $form = $this->createForm(AvatarType::class, $avatar);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar->setCreatedAt(new \DateTime());
            $avatar->setUpdatedAt(new \DateTime());
            $this->avatarService->save($avatar);
            $this->addFlash('success', 'message_added_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'avatar/create.html.twig',
            ['form' => $form->createView(), 'avatar' => $avatar, 'searchForm' => $searchForm->createView(),
            ]
        );
    }



    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Avatar                          $avatar    Avatar entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/edit/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="avatar_edit",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="avatar",
     * )
     */
    public function edit(Request $request, Avatar $avatar): Response
    {
        $form = $this->createForm(AvatarType::class, $avatar, ['method' => 'PUT']);
        $form->handleRequest($request);

        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar->setUpdatedAt(new \DateTime());
            $this->avatarService->save($avatar);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'avatar/edit.html.twig',
            [
                'form' => $form->createView(),
                'avatar' => $avatar,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }



}
