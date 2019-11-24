<?php

namespace Andchir\CommentsBundle\Service;

class CommentsManager {

    /** @var array */
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function createComment()
    {
        $className = $this->config['comment_class'] ?? '';
        if (!$className) {
            return null;
        }
        return new $className;
    }

    public function getConfig()
    {
        return $this->config;
    }
}
