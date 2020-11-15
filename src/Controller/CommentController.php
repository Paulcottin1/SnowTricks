<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * Load more Comments
     * @Route("/loadComments", name="loadComments")
     * @param CommentRepository $repository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function loadMoreComments(CommentRepository $repository, Request $request, PaginatorInterface $paginator) : Response
    {
        $trick = $request->query->getInt('trick');
        $comments = $repository->findBy(['trick' => $trick, 'approved' => '1'], ['id' => 'DESC']);
        $result = $paginator->paginate(
            $comments,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)
        );

        return $this->render('paging/paging-comments.html.twig', [
            'comments' => $result
        ]);
    }

    /**
     * @Route("/{id}/approved", name="approved", methods={"GET","POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Comment $comment
     * @return Response
     */
    public function approved(Comment $comment) : Response
    {
        $comment->setApproved(true);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash(
            'notice',
            'Le commentaire a bien été approuvé'
        );
        return $this->redirectToRoute('admin_comments');
    }

    /**
     * @Route("/{id}/delete-comment", name="delete_comment", methods={"GET","POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Comment $comment
     * @return Response
     */
    public function delete(Comment $comment) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Le commentaire a bien été supprimé'
        );
        return $this->redirectToRoute('admin_comments');
    }
}
