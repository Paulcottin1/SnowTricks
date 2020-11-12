<?php


namespace App\Controller;


use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("admin/moderation-commentaires", name="admin_comments")
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function moderationComments(CommentRepository $commentRepository) : Response
    {
        $comments = $commentRepository->findBy(['approved' => false]);

        return $this->render('admin/moderation-comments.html.twig', [
            'comments' => $comments
        ]);
    }
}
