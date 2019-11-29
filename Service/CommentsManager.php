<?php

namespace Andchir\CommentsBundle\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CommentsManager {

    /** @var array */
    protected $config;

    public function __construct(ParameterBagInterface $params, array $config = [])
    {
        if (empty($config) && $params->has('comments_config')) {
            $this->config = $params->get('comments_config');
        } else {
            $this->config = $config;
        }
    }

    /**
     * @param int $threadId
     * @return mixed
     */
    public function createComment($threadId = '')
    {
        $className = $this->config['comment_class'] ?? '';
        if (!$className) {
            return null;
        }
        $class = new $className;
        $class->setThreadId($threadId);
        return $class;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function getOptionValue($key)
    {
        return $this->config[$key] ?? '';
    }

    /**
     * @return mixed|string
     */
    public function getCommentsClassName()
    {
        return $this->config['comment_class'] ?? '';
    }
}
