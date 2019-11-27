<?php

namespace Andchir\CommentsBundle\Document;

abstract class CommentAbstract implements CommentInterface {

    /** @var string */
    protected $threadId;
    /** @var string */
    protected $comment;
    /** @var int */
    protected $vote;
    /** @var string */
    protected $status;
    /** @var mixed */
    protected $author;
    /** @var \DateTime */
    protected $createdTime;
    /** @var \DateTime */
    protected $publishedTime;

    public function __construct()
    {
        $this->setStatus(self::STATUS_PENDING);
    }

    /**
     * @return string
     */
    public function getThreadId()
    {
        return $this->threadId;
    }

    /**
     * @param $threadId
     * @return $this
     */
    public function setThreadId($threadId)
    {
        $this->threadId = $threadId;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return int
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * @param $vote
     * @return $this
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param $author
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * @param $createdTime
     * @return $this
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedTime()
    {
        return $this->publishedTime;
    }

    /**
     * @param $publishedTime
     * @return $this
     */
    public function setPublishedTime($publishedTime)
    {
        $this->publishedTime = $publishedTime;
        return $this;
    }

    public function prePersist()
    {
        $this->createdTime = new \DateTime();
        $this->publishedTime = new \DateTime();
    }
}
