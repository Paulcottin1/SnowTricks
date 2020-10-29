<?php


namespace App\Controller;


use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
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
        $comments = $repository->findBy(['trick' => $trick], ['id' => 'DESC']);
        $result = $paginator->paginate(
            $comments,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)
        );

        return $this->render('paging/paging-comments.html.twig', [
            'comments' => $result
        ]);
    }

}
