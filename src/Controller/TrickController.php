<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Manager\Uploader;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/trick")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick_index", methods={"GET"})
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     * @param Request $request
     * @param Uploader $uploader
     * @return Response
     */
    public function new(Request $request, Uploader $uploader): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();
            $images = $form['images']->getData();
            $path = $this->getParameter('images_directory');
            $uploader->upload($image, $path, $trick);

            foreach($images as $img) {
               $uploader->uploadMultiple($img, $path, $trick);
            }

            if(empty($image)) {
                $this->addFlash(
                    'notice',
                    'Vous devez ajouter une image de présentation'
                );
                return $this->redirectToRoute('trick_new');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();
            
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="trick_show", methods={"GET"})
     * @param Trick $trick
     * @param ImageRepository $imageRepository
     * @param VideoRepository $videoRepository
     * @return Response
     */
    public function show(Trick $trick, ImageRepository $imageRepository, VideoRepository $videoRepository): Response
    {
        $images = $imageRepository->findBy(['trick' => $trick->getId()]);
        $videos = $videoRepository->findBy(['trick' => $trick->getId()]);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'images' => $images,
            'videos' => $videos
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="trick_edit", methods={"GET","POST"})
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @param Trick $trick
     * @param Uploader $uploader
     * @return Response
     */
    public function edit(Request $request, Trick $trick, Uploader $uploader): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();
            $images = $form['images']->getData();
            $path = $this->getParameter('images_directory');
            $uploader->upload($image, $path, $trick);

            foreach($images as $img) {
                $uploader->uploadMultiple($img, $path, $trick);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trick_delete", methods={"DELETE"})
     * @Security("has_role('ROLE_USER') and user == trick.getUser()",
     *     message = "Ce n'est pas votre trick, vous ne pouvez pas le supprimer")
     * @param Request $request
     * @param Trick $trick
     * @return Response
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
            $image = $this->getParameter('images_directory') . $trick->getImageName();
            $images = $trick->getImages();
            foreach($images as $img) {
                $img = $img->getName();
                $deleteImg = $this->getParameter('images_directory') . $img;
                unlink($deleteImg);
            }
            unlink($image);
        }

        return $this->redirectToRoute('trick_index');
    }
}
