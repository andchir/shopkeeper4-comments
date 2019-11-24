<?php

namespace Andchir\CommentsBundle\Controller;

use Andchir\CommentsBundle\Document\CommentAbstract;
use Andchir\CommentsBundle\Form\Type\AddCommentType;
use Andchir\CommentsBundle\Service\CommentsManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Andchir\CommentsBundle\Controller
 *
 * @Route("/comments")
 */
class DefaultController extends Controller
{
    /** @var CommentsManager */
    protected $commentsManager;
    /** @var DocumentManager */
    protected $dm;

    public function __construct(CommentsManager $commentsManager, DocumentManager $dm)
    {
        $this->commentsManager = $commentsManager;
        $this->dm = $dm;
    }

    /**
     * @Route("/{threadId}", name="comments_list", methods={"GET"})
     * @param Request $request
     * @param CommentsManager $commentsManager
     * @return Response
     */
    public function getThreadAction(Request $request, CommentsManager $commentsManager, $threadId)
    {
        $form = $this->createForm(AddCommentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            var_dump($this->commentsManager->getConfig(), $comment);

        }

        return $this->render('@Comments/Default/comments.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add", name="comment_add", methods={"POST"})
     * @param Request $request
     * @param CommentsManager $commentsManager
     * @return Response
     */
    public function addCommentAction(Request $request, CommentsManager $commentsManager)
    {

        $comment = $commentsManager->createComment();

        $threadId = 1;

        $form = $this->createForm(AddCommentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $comment = $this->container->get('comments')->createComment();

            $comment
                ->setThreadId($threadId)
                ->setComment($formData['comment'])
                ->setVote($formData['vote']);

            $this->dm->persist($comment);
            $this->dm->flush();

            var_dump($comment);

        }

        return $this->render('@Comments/Default/add_comment_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
