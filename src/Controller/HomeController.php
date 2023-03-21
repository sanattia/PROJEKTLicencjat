<?php
/**
 * Home controller.
 */

namespace App\Controller;

use App\Form\HomeType;
use App\Form\PickerType;
use App\Service\TrasaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController.
 */
class HomeController extends AbstractController
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
     * Index action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET", "POST"},
     *     name="home",
     * )
     */
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        $pickerForm = $this->createForm(PickerType::class);
        $pickerForm->handleRequest($request);

        return $this->render(
            'index.html.twig',
            ['searchForm' => $searchForm->createView(), 'picker' => $pickerForm->createView(),
            ]
        );
    }

    /**
     * Index page action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/about",
     *     methods={"GET", "POST"},
     *     name="about",
     * )
     */
    public function indexAbout(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        $pickerForm = $this->createForm(PickerType::class);
        $pickerForm->handleRequest($request);

        return $this->render(
            'about.html.twig',
            ['searchForm' => $searchForm->createView(), 'picker' => $pickerForm->createView(),
            ]
        );
    }

    /**
     * Index page action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/admin",
     *     methods={"GET", "POST"},
     *     name="admin",
     * )
     */
    public function indexAdmin(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        $pickerForm = $this->createForm(PickerType::class);
        $pickerForm->handleRequest($request);

        return $this->render(
            'admin.html.twig',
            ['searchForm' => $searchForm->createView(), 'picker' => $pickerForm->createView(),
            ]
        );
    }

    /**
     * Index page action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/informacja",
     *     methods={"GET", "POST"},
     *     name="informacja",
     * )
     */
    public function indexInformacja(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        $pickerForm = $this->createForm(PickerType::class);
        $pickerForm->handleRequest($request);

        return $this->render(
            'informacja.html.twig',
            ['searchForm' => $searchForm->createView(), 'picker' => $pickerForm->createView(),
            ]
        );
    }
}
