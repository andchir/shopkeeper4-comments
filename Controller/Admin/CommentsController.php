<?php

namespace Andchir\CommentsBundle\Controller\Admin;

use Andchir\CommentsBundle\Document\CommentInterface;
use Andchir\CommentsBundle\Service\CommentsManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

if (class_exists('\App\Controller\Admin\StorageControllerAbstract')) {

    /**
     * Class CommentsController
     * @Route("/admin/comments")
     */
    class CommentsController extends \App\Controller\Admin\StorageControllerAbstract {

        /** @var EventDispatcherInterface */
        protected $eventDispatcher;
        /** @var CommentsManager */
        protected $commentsManager;

        public function __construct(
            ParameterBagInterface $params,
            DocumentManager $dm,
            TranslatorInterface $translator,
            EventDispatcherInterface $eventDispatcher,
            CommentsManager $commentsManager
        )
        {
            parent::__construct($params, $dm, $translator);
            $this->eventDispatcher = $eventDispatcher;
            $this->commentsManager = $commentsManager;
        }
        
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
         * @param null $itemId
         * @return JsonResponse
         * @throws \Doctrine\ODM\MongoDB\MongoDBException
         */
        public function createUpdate($data, $itemId = null)
        {
            /** @var CommentInterface $item */
            if($itemId){
                /** @var CommentInterface $item */
                $item = $this->getRepository()->find($itemId);
                if(!$item){
                    return $this->setError($this->translator->trans('Item not found.', [], 'validators'));
                }
            } else {
                $item = $this->commentsManager->createComment();
                $item->setAuthor($this->getUser());
            }
            $isActive = $item->getIsActive();

            $item
                ->setStatus($data['status'])
                ->setThreadId($data['threadId'])
                ->setVote($data['vote'])
                ->setComment($data['comment'])
                ->setReply($data['reply']);

            if ($item->getIsActive() && $isActive !== $item->getIsActive()) {
                $item->setPublishedTime(new \DateTime());
            }

            if (!$item->getId()) {
                $this->dm->persist($item);
            }
            $this->dm->flush();

            // Dispatch event before create
            $event = new GenericEvent($item);
            $this->eventDispatcher->dispatch($event, CommentInterface::COMMENT_STATUS_UPDATED)->getSubject();

            return $this->json($item, 200, [], ['groups' => ['details']]);
        }

        /**
         * @return \Andchir\CommentsBundle\Repository\CommentRepositoryInterface|ObjectRepository
         */
        public function getRepository()
        {
            return $this->dm->getRepository('App\MainBundle\Document\Comment');
        }
    }

} else {
    class CommentsController extends AbstractController { }
}
