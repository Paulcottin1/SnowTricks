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
            $request->query->getInt('limit', 10)
        );
        return $this->render('home/index.html.twig', [
            'tricks' => $result
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Trick $trick): Response
    {
        return $this->render('home/show.html.twig', [
            'trick' => $trick,
        ]);
    }
}
