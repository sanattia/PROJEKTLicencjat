<?php
/**
 * Wpis controller.
 */

namespace App\Controller;

use App\Entity\Wpis;
use App\Entity\Trasa;
use App\Form\HomeType;
use App\Form\PickerType;
use App\Form\TrasaType;
use App\Form\WpisType;
use App\Service\TrasaService;
use App\Service\WpisService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WpisController.
 *
 * @Route("/wpis")
 *
 * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
 */
class WpisController extends AbstractController
{
    /**
     * Wpis service.
     *
     * @var WpisService
     */
    private $wpisService;

    /**
     * @var TrasaService
     */
    private $trasaService;

    /**
     * WpisController constructor.
     *
     * @param WpisService $wpisService Wpis service
     * @param TrasaService $trasaService Trasa service
     */
    public function __construct(WpisService $wpisService, TrasaService $trasaService)
    {
        $this->wpisService = $wpisService;
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
     *     name="wpis_index",
     * )
     */
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $pagination = $this->wpisService->getPaginatedList(
            $request->query->getInt('page', 1),
            $this->getUser()
        );

        return $this->render('wpis/index.html.twig', ['pagination' => $pagination, 'searchForm' => $searchForm->createView()]);
    }

    /**
     * Show action.
     *
     * @param Wpis  $wpis
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="wpis_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @IsGranted(
     *     "SHOW",
     *     subject="wpis",
     * )
     */
    public function show(Wpis $wpis, Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        if ($wpis->getAuthor() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );

            return $this->redirectToRoute('wpis_index');
        }

        return $this->render(
            'wpis/show.html.twig',
            ['wpis' => $wpis,
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
     *     name="wpis_create",
     * )
     *
     */
    public function create(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();
        $wpis = new Wpis();
        $wpis->setAuthor($user);
        $trasa = $request->get('id');
        $form = $this->createForm(WpisType::class, $wpis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wpis->setCreatedAt(new \DateTime());
            $wpis->setUpdatedAt(new \DateTime());
            $this->wpisService->save($wpis);
            $this->addFlash('success', 'message_added_successfully');

            return $this->redirectToRoute('wpis_index');
        }

        $pickerForm = $this->createForm(PickerType::class);
        $pickerForm->handleRequest($request);

        $filters = [];
        $filters['region_id'] = $request->query->getInt('filters_region_id');
        $filters['trudnosc_id'] = $request->query->getInt('filters_trudnosc_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        $pagination = $this->trasaService->getMiniPaginatedList(
            $request->query->getInt('page', 1),
            $filters, $user
        );
        if ($request->isMethod('post')) {
            $filters['tag_id'] = $request->request->get('picker')['tags'];
            $filters['region_id'] = $request->request->get('picker')['region'];
            $filters['trudnosc_id'] = $request->request->get('picker')['trudnosc'];
            $pagination = $this->trasaService->getMiniPaginatedList(
                $request->query->getInt('page', 1),
                $filters
            );
        }




        return $this->render(
            'wpis/create.html.twig',
            ['form' => $form->createView(), 'wpis' => $wpis, 'pagination' => $pagination,
                'searchForm' => $searchForm->createView(), 'picker' => $pickerForm->createView(),
            ]
        );
    }



    /**
     *  CreateFrom action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Wpis                          $wpis    Wpis entity
     * @param \App\Entity\Trasa                          $trasa    Trasa entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/create",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="wpis_create_from",
     * )
     *
     */
    public function createFrom(Request $request, Trasa $trasa): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();
        $wpis = new Wpis();
        $wpis->setAuthor($user);
        $wpis->setTrasa($trasa);
        $form = $this->createForm(WpisType::class, $wpis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wpis->setCreatedAt(new \DateTime());
            $wpis->setUpdatedAt(new \DateTime());
            $this->wpisService->save($wpis);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('wpis_index');
        }

        return $this->render(
            'wpis/createFrom.html.twig',
            [
                'form' => $form->createView(),
                'wpis' => $wpis,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Wpis                          $wpis    Wpis entity
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
     *     name="wpis_edit",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="wpis",
     * )
     */
    public function edit(Request $request, Wpis $wpis): Response
    {
        $form = $this->createForm(WpisType::class, $wpis, ['method' => 'PUT']);
        $form->handleRequest($request);

        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wpis->setUpdatedAt(new \DateTime());
            $this->wpisService->save($wpis);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('wpis_index');
        }

        return $this->render(
            'wpis/edit.html.twig',
            [
                'form' => $form->createView(),
                'wpis' => $wpis,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Wpis                          $wpis    Wpis entity
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
     *     name="wpis_delete",
     * )
     *
     * @IsGranted(
     *     "DELETE",
     *     subject="wpis",
     * )
     */
    public function delete(Request $request, Wpis $wpis): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $form = $this->createForm(FormType::class, $wpis, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->wpisService->delete($wpis);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('wpis_index');
        }

        return $this->render(
            'wpis/delete.html.twig',
            [
                'form' => $form->createView(),
                'wpis' => $wpis,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

}
