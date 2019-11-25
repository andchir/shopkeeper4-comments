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
