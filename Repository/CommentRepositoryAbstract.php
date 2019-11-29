<?php

namespace Andchir\CommentsBundle\Repository;

use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

abstract class CommentRepositoryAbstract extends DocumentRepository {

    /**
     * @param string $threadId
     * @param string $status
     * @return array
     */
    public function findByStatus($threadId, $status)
    {
        return $this->findBy([
            'threadId' => $threadId,
            'status' => $status
        ], [
            'publishedTime' => 'desc'
        ]);
    }

    /**
     * @param string $threadId
     * @return array
     */
    public function findAllByThread($threadId)
    {
        return $this->findBy([
            'threadId' => $threadId
        ], [
            'publishedTime' => 'desc'
        ]);
    }
}
