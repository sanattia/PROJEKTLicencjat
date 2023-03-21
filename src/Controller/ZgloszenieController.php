<?php
/**
 * Zgloszenie controller.
 */

namespace App\Controller;

use App\Entity\Zgloszenie;
use App\Form\HomeType;
use App\Form\PickerType;
use App\Form\ZgloszenieType;
use App\Service\ZgloszenieService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ZgloszenieController.
 *
 * @Route("/zgloszenie")
 *
 * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
 */
class ZgloszenieController extends AbstractController
{
    /**
     * Zgloszenie service.
     *
     * @var ZgloszenieService
     */
    private $zgloszenieService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;



    /**
     * ZgloszenieController constructor.
     *
     * @param ZgloszenieService $zgloszenieService Zgloszenie service
     * @param TranslatorInterface  $translator  Translator
     *
     */
    public function __construct(ZgloszenieService $zgloszenieService, TranslatorInterface $translator)
    {
        $this->zgloszenieService = $zgloszenieService;
        $this->translator = $translator;
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
     *     name="zgloszenie_index",
     * )
     */
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $pagination = $this->zgloszenieService->getPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser()
        );

        return $this->render('zgloszenie/index.html.twig', ['pagination' => $pagination, 'searchForm' => $searchForm->createView()]);
    }


    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/admin",
     *     name="zgloszenie_admin",
     * )
     */
    public function indexAdmin(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $pagination = $this->zgloszenieService->createPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('zgloszenie/admin.html.twig', ['pagination' => $pagination, 'searchForm' => $searchForm->createView()]);
    }

    /**
     * Show action.
     *
     * @param Zgloszenie  $zgloszenie
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="zgloszenie_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @IsGranted(
     *     "SHOW",
     *     subject="zgloszenie",
     * )
     */
    public function show(Zgloszenie $zgloszenie, Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        if ($zgloszenie->getAuthor() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('zgloszenie_index');
        }

        return $this->render(
            'zgloszenie/show.html.twig',
            ['zgloszenie' => $zgloszenie,
                'searchForm' => $searchForm->createView()
            ]
        );
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
     *     name="zgloszenie_create",
     * )
     *
     */
    public function create(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();
        $zgloszenie = new Zgloszenie();
        $zgloszenie->setAuthor($user);
        $form = $this->createForm(ZgloszenieType::class, $zgloszenie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $zgloszenie->setCreatedAt(new \DateTime());
            $zgloszenie->setUpdatedAt(new \DateTime());
            $this->zgloszenieService->save($zgloszenie);
            $this->addFlash('success', 'message_added_successfully');

            return $this->redirectToRoute('zgloszenie_index');
        }


        return $this->render(
            'zgloszenie/create.html.twig',
            ['form' => $form->createView(), 'zgloszenie' => $zgloszenie,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }



    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Zgloszenie                          $zgloszenie    Zgloszenie entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="zgloszenie_edit",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="zgloszenie",
     * )
     */
    public function edit(Request $request, Zgloszenie $zgloszenie): Response
    {
        $form = $this->createForm(ZgloszenieType::class, $zgloszenie, ['method' => 'PUT']);
        $form->handleRequest($request);

        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $zgloszenie->setUpdatedAt(new \DateTime());
            $this->zgloszenieService->save($zgloszenie);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('zgloszenie_index');
        }

        return $this->render(
            'zgloszenie/edit.html.twig',
            [
                'form' => $form->createView(),
                'zgloszenie' => $zgloszenie,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Zgloszenie                          $zgloszenie    Zgloszenie entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="zgloszenie_delete",
     * )
     *
     * @IsGranted(
     *     "DELETE",
     *     subject="zgloszenie",
     * )
     */
    public function delete(Request $request, Zgloszenie $zgloszenie): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $form = $this->createForm(FormType::class, $zgloszenie, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->zgloszenieService->delete($zgloszenie);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('zgloszenie_index');
        }

        return $this->render(
            'zgloszenie/delete.html.twig',
            [
                'form' => $form->createView(),
                'zgloszenie' => $zgloszenie,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

}
