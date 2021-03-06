<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class HomeController extends Controller
{
    /**
     * Display the home page
     *
     * @Route("/", name="home")
     */
    public function index(TrickRepository $repository, Request $request) : Response
    {
        $tricks = $repository->findAll();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $tricks,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );
        return $this->render('home/index.html.twig', [
            'tricks' => $result
        ]);
    }

    /**
     * Load more tricks
     *
     * @Route("/load", name="load")
     * @param TrickRepository $repository
     * @param Request $request
     * @return Response
     */
    public function loadMore(TrickRepository $repository, Request $request) : Response {
        $tricks = $repository->findAll();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $tricks,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)
        );

        return $this->render('paging/paging-tricks.html.twig', [
            'tricks' => $result
        ]);
    }
}
