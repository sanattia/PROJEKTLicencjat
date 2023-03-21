<?php
/**
 * Trasa controller.
 */

namespace App\Controller;

use App\Entity\Trasa;
use App\Form\HomeType;
use App\Form\PickerType;
use App\Form\TrasaNewType;
use App\Form\TrasaType;
use App\Service\TrasaService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TrasaController.
 *
 * @Route("/trasa")
 *
 * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
 */
class TrasaController extends AbstractController
{
    /**
     * Trasa service.
     *
     * @var TrasaService
     */
    private TrasaService $trasaService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * TrasaController constructor.
     *
     * @param TrasaService $trasaService Trasa service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(TrasaService $trasaService, TranslatorInterface $translator)
    {
        $this->trasaService = $trasaService;
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
     *     methods={"POST", "GET"},
     *     name="trasa_index",
     * )
     */
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        $pickerForm = $this->createForm(PickerType::class);
        $pickerForm->handleRequest($request);

        $filters = [];
        $filters['region_id'] = $request->query->getInt('filters_region_id');
        $filters['trudnosc_id'] = $request->query->getInt('filters_trudnosc_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        $pagination = $this->trasaService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );
        if ($request->isMethod('post')) {
            $filters['tag_id'] = $request->request->get('picker')['tags'];
            $filters['region_id'] = $request->request->get('picker')['region'];
            $filters['trudnosc_id'] = $request->request->get('picker')['trudnosc'];
            $pagination = $this->trasaService->getPaginatedList(
                $request->query->getInt('page', 1),
                $filters
            );
        }

        return $this->render(
            'trasa/index.html.twig',
            ['pagination' => $pagination,
                'searchForm' => $searchForm->createView(), 'picker' => $pickerForm->createView(),]
        );
    }




    /**
     * Show action.
     *
     * @param Trasa           $trasa
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="trasa_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Trasa $trasa, Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        return $this->render(
            'trasa/show.html.twig',
            [
                'trasa' => $trasa,
                'searchForm' => $searchForm->createView()
            ]
        );
    }

    /**
     * CreateNew action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/createnew",
     *     methods={"GET", "POST"},
     *     name="trasa_createnew",
     * )
     *
     * @IsGranted("ROLE_USER")
     * )
     */
    public function createNew(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $trasa = new Trasa();
        $user = $this->getUser();
        $trasa -> setAuthor($user);
        $trasa -> setTest("user");
        $form = $this->createForm(TrasaNewType::class, $trasa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trasa->setCreatedAt(new \DateTime());
            $trasa->setUpdatedAt(new \DateTime());
            $this->trasaService->save($trasa);
            $this->addFlash('success', 'message_added_successfully');

            return $this->redirectToRoute('wpis_create');
        }

        return $this->render(
            'trasa/createnew.html.twig',
            ['form' => $form->createView(), 'trasa' => $trasa, 'searchForm' => $searchForm->createView(),
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
     *     name="trasa_create",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     * )
     */
    public function create(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $trasa = new Trasa();
        $user = $this->getUser();
        $trasa -> setAuthor($user);
        $trasa -> setTest("admin");
        $form = $this->createForm(TrasaType::class, $trasa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trasa->setCreatedAt(new \DateTime());
            $trasa->setUpdatedAt(new \DateTime());
            $this->trasaService->save($trasa);
            $this->addFlash('success', 'message_added_successfully');

            return $this->redirectToRoute('trasa_index');
        }

        return $this->render(
            'trasa/create.html.twig',
            ['form' => $form->createView(), 'trasa' => $trasa, 'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Trasa                          $trasa    Trasa entity
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
     *     name="trasa_edit",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     * )
     */
    public function edit(Request $request, Trasa $trasa): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        $form = $this->createForm(TrasaType::class, $trasa, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trasa->setUpdatedAt(new \DateTime());
            $this->trasaService->save($trasa);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('trasa_index');
        }

        return $this->render(
            'trasa/edit.html.twig',
            [
                'form' => $form->createView(),
                'trasa' => $trasa,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Trasa                          $trasa    Trasa entity
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
     *     name="trasa_delete",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Trasa $trasa): Response
    {
        $form = $this->createForm(FormType::class, $trasa, ['method' => 'DELETE']);
        $form->handleRequest($request);
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);


        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->trasaService->delete($trasa);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('trasa_index');
        }

        return $this->render(
            'trasa/delete.html.twig',
            [
                'form' => $form->createView(),
                'trasa' => $trasa,
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

}
