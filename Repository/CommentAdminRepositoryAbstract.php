<?php

namespace Andchir\CommentsBundle\Repository;

use App\Repository\BaseRepository;

abstract class CommentAdminRepositoryAbstract extends BaseRepository
{

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
