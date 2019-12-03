<?php

namespace Andchir\CommentsBundle\Controller\Admin;

use Andchir\CommentsBundle\Document\CommentInterface;
use Andchir\CommentsBundle\Repository\CommentRepositoryAbstract;
use App\Controller\Admin\StorageControllerAbstract;
use App\MainBundle\Document\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CommentsController
 * @Route("/admin/comments")
 */
class CommentsController extends StorageControllerAbstract
{

    /**
     * @param $data
     * @param int $itemId
     * @return array
     */
    public function validateData($data, $itemId = null)
    {
        if (empty($data)) {
            return ['success' => false, 'msg' => 'Data is empty.'];
        }
        return ['success' => true];
    }

    /**
     * @param $data
     * @param int $itemId
     * @return JsonResponse
     */
    public function createUpdate($data, $itemId = null)
    {
        /** @var TranslatorInterface $translator */
        $translator = $this->get('translator');
        /** @var \Doctrine\ODM\MongoDB\DocumentManager $dm */
        $dm = $this->get('doctrine_mongodb')->getManager();

        /** @var CommentInterface $item */
        if($itemId){
            /** @var CommentInterface $item */
            $item = $this->getRepository()->find($itemId);
            if(!$item){
                return $this->setError($translator->trans('Item not found.', [], 'validators'));
            }
        } else {
            $item = new Comment();
            $item->setAuthor($this->getUser());
        }

        $item
            ->setStatus($data['status'])
            ->setThreadId($data['threadId'])
            ->setVote($data['vote'])
            ->setComment($data['comment'])
            ->setReply($data['reply']);

        if (!$item->getId()) {
            $dm->persist($item);
        }
        $dm->flush();

        return $this->json($item, 200, [], ['groups' => ['details']]);
    }

    /**
     * @return CommentRepositoryAbstract
     */
    public function getRepository()
    {
        return $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository(Comment::class);
    }
}
