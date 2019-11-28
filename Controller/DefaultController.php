<?php

namespace Andchir\CommentsBundle\Controller;

use Andchir\CommentsBundle\Document\CommentInterface;
use Andchir\CommentsBundle\Form\Type\AddCommentType;
use Andchir\CommentsBundle\Service\CommentsManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

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
    public function getThreadAction(Request $request, $threadId)
    {
        $user = $this->getUser();
        /** @var CommentInterface $comment */
        $comment = $this->commentsManager->createComment($threadId);

        $form = $this->createForm(AddCommentType::class, $comment);

        if ($this->isGranted('ROLE_ADMIN')) {
            $comments = $this->dm
                ->getRepository($this->commentsManager->getCommentsClassName())
                ->findAllByThread($threadId);
        } else {
            $comments = $this->dm
                ->getRepository($this->commentsManager->getCommentsClassName())
                ->findByStatus($threadId, CommentInterface::STATUS_PUBLISHED);
        }

        return $this->render('@Comments/Default/comments.html.twig', [
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/add", name="comment_add", methods={"POST"})
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function addCommentAction(Request $request, TranslatorInterface $translator)
    {
        /** @var CommentInterface $comment */
        $comment = $this->commentsManager->createComment();
        $form = $this->createForm(AddCommentType::class, $comment);

        $form->handleRequest($request);

        if (!$this->isGranted('ROLE_USER')) {
            $form->addError(new FormError($translator->trans('Only authorized users can post reviews.')));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setAuthor($this->getUser());
            $this->dm->persist($comment);
            $this->dm->flush();

            $this->addFlash('messages', 'Thanks! Comment will be published after verification.');

            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'success' => true,
                    'result' => $comment,
                    'form' => $this->renderView('@Comments/Default/add_comment_form.html.twig', [
                        // Reset form
                        'form' => $this->createForm(AddCommentType::class, $this->commentsManager->createComment($comment->getThreadId()))->createView()
                    ])
                ], 200, [], [
                    'groups' => ['details']
                ]);
            } else {
                return $this->redirectToRoute('comments_list', ['threadId' => $comment->getThreadId()]);
            }
        }

        if ($request->isXmlHttpRequest()) {
            return $this->setError([
                'success' => false,
                'error' => (string) $form->getErrors(true, false),
                'form' => $this->renderView('@Comments/Default/add_comment_form.html.twig', [
                    'form' => $form->createView()
                ])
            ]);
        } else {
            return $this->render('@Comments/Default/add_comment_form.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @param $message
     * @param int $status
     * @return JsonResponse
     */
    public function setError($message, $status = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        $response = new JsonResponse($message);
        $response = $response->setStatusCode($status);
        return $response;
    }
}
