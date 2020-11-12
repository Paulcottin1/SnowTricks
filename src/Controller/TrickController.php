<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Manager\Uploader;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
use Knp\Component\Pager\PaginatorInterface;
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
            $trick
                ->setUser($this->getUser())
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime());

            foreach($images as $img) {
               $uploader->uploadMultiple($img, $path, $trick);
            }

            if(strlen($trick->getTitle()) > 13 ) {
                $this->addFlash(
                    'warning',
                    'Le titre doit contenir 13 caractères maximum'
                );
                return $this->redirectToRoute('trick_new');
            }

            if(strlen($trick->getContent()) > 1500 ) {
                $this->addFlash(
                    'warning',
                    'La description doit contenir 1500 caractères maximum'
                );
                return $this->redirectToRoute('trick_new');
            }

            if(empty($image)) {
                $this->addFlash(
                    'warning',
                    'Vous devez ajouter une image de présentation'
                );
                return $this->redirectToRoute('trick_new');
            }

            foreach ($trick->getVideos() as $video) {
                $this->setYoutubeKey($video);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Votre trick a bien été crée !'
            );
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="trick_show", methods={"GET","POST"})
     * @param Trick $trick
     * @param ImageRepository $imageRepository
     * @param VideoRepository $videoRepository
     * @param CommentRepository $commentRepository
     * @param Request $request
     * @param Paginator $paginator
     * @return Response
     */
    public function show(Trick $trick, ImageRepository $imageRepository, VideoRepository $videoRepository, CommentRepository $commentRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser())
                ->setTrick($trick)
                ->setCommentedAt(new \DateTime())
                ->setApproved(false);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($comment);
                $entityManager->flush();

                $this->addFlash(
                    'notice',
                    'Votre commentaire a bien été pris en compte. Il sera posté après validation'
                );
                return $this->redirectToRoute('trick_show', ['slug' => $trick->getSlug()]);
        }
        $images = $imageRepository->findBy(['trick' => $trick->getId()]);
        $videos = $videoRepository->findBy(['trick' => $trick->getId()]);
        $comments = $commentRepository->findBy(['trick' => $trick->getId()], ['id' => 'DESC']);

        $result = $paginator->paginate(
            $comments,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)
        );

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'images' => $images,
            'videos' => $videos,
            'comments' => $result,
            'form' => $form->createView(),
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
            $trick->setUpdatedAt(new \DateTime());

            if(strlen($trick->getTitle()) > 13 ) {
                $this->addFlash(
                    'warning',
                    'Le titre doit contenir 13 caractères maximum'
                );
                return $this->redirectToRoute('trick_edit', array('slug' => $trick->getSlug()));
            }

            if(strlen($trick->getContent()) > 1500 ) {
                $this->addFlash(
                    'warning',
                    'La description doit contenir 1500 caractères maximum'
                );
                return $this->redirectToRoute('trick_edit', array('slug' => $trick->getSlug()));
            }

            foreach($images as $img) {
                $uploader->uploadMultiple($img, $path, $trick);
            }

            foreach ($trick->getVideos() as $video) {
                $this->setYoutubeKey($video);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'notice',
                'Le trick a bien été modifié !'
            );
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

        $this->addFlash(
            'notice',
            'Le trick a été supprimé'
        );
        return $this->redirectToRoute('home');
    }

    /**
     * @param Video $video
     * @return Video
     */
    public function setYoutubeKey(Video $video)
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video->getUrl(), $match)) {
            $videoId = $match[1];
            $video->setUrl('https://www.youtube.com/embed/' . $videoId);
        }

        return $video;
    }
}
