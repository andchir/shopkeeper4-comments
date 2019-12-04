<?php

namespace Andchir\CommentsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class CommentsManager {

    /** @var ObjectManager */
    protected $dm;
    /** @var array */
    protected $config;

    public function __construct(ContainerInterface $container, array $config = [])
    {
        if (empty($config) && $container->hasParameter('comments_config')) {
            $this->config = $container->getParameter('comments_config');
        } else {
            $this->config = $config;
        }
        if ($container->has('doctrine_mongodb.odm.default_document_manager')) {
            $this->dm = $container->get('doctrine_mongodb.odm.default_document_manager');
        } else {
            $this->dm = $container->get('doctrine.orm.default_entity_manager');
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

    /**
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->dm->getRepository($this->getCommentsClassName());
    }

    /**
     * @return ObjectManager
     */
    public function getEntityManager() {
        return $this->dm;
    }
}
