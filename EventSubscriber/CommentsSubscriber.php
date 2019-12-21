<?php

namespace Andchir\CommentsBundle\EventSubscriber;

use Andchir\CommentsBundle\Document\CommentInterface;
use Andchir\CommentsBundle\Service\CommentsManager;
use App\MainBundle\Document\ContentType;
use App\Service\CatalogService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class CommentsSubscriber implements EventSubscriberInterface
{
    
    /** @var ContainerInterface */
    private $container;
    /** @var CommentsManager */
    protected $commentsManager;
    /** @var CatalogService */
    protected $catalogService;
    /** @var DocumentManager */
    protected $dm;

    public function __construct(
        ContainerInterface $container,
        CommentsManager $commentsManager,
        CatalogService $catalogService,
        DocumentManager $dm
    )
    {
        $this->container = $container;
        $this->commentsManager = $commentsManager;
        $this->catalogService = $catalogService;
        $this->dm = $dm;
    }

    public static function getSubscribedEvents()
    {
        return [
            CommentInterface::COMMENT_BEFORE_CREATE => 'onBeforeCreate',
            CommentInterface::COMMENT_STATUS_UPDATED => 'onStatusUpdated'
        ];
    }

    public function onBeforeCreate(GenericEvent $event)
    {
        /** @var CommentInterface $comment */
        $comment = $event->getSubject();

        // var_dump($comment);

    }

    public function onStatusUpdated(GenericEvent $event)
    {
        /** @var CommentInterface $comment */
        $comment = $event->getSubject();
        
        $threadId = $comment->getThreadId();
        $averageRating = $this->commentsManager->getAverageRating($threadId);
        
        list($contentTypeName, $documentId) = explode('_', $threadId);
        
        /** @var ContentType $contentType */
        $contentType = $this->dm->getRepository(ContentType::class)
            ->findOneBy(['name' => $contentTypeName]);
        
        if (!$contentType || !($ratingFieldName = $contentType->getFieldByChunkName('rating'))) {
            return;
        }
        
        $collection = $this->catalogService->getCollection($contentType->getCollection());

        $document = $collection->findOne(['_id' => (int) $documentId]);
        if (!$document) {
            return;
        }

        // Update rating value
        $collection->updateOne(
            ['_id' => (int) $documentId],
            [
                '$set' => [$ratingFieldName => $averageRating]
            ]
        );
    }
}
