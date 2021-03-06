<?php

namespace App\Service;

use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class MarkdownHelper.
 */
class MarkdownHelper
{
    /** @var AdapterInterface */
    private $cache;
    /** @var MarkdownInterface */
    private $markdown;
    /** @var LoggerInterface */
    private $logger;
    /** @var bool */
    private $isDebug;
    /** @var Security */
    private $security;

    /**
     * MarkdownHelper constructor.
     *
     * @param AdapterInterface  $cache
     * @param MarkdownInterface $markdown
     * @param LoggerInterface   $markdownLogger
     * @param bool              $isDebug
     * @param Security          $security
     */
    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger, bool $isDebug, Security $security)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
        $this->isDebug = $isDebug;
        $this->security = $security;
    }

    /**
     * @param string $source
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @return string
     */
    public function parse(string $source): string
    {
        dump($this->cache);

        if (false !== mb_stripos($source, 'bacon')) {
            $this->logger->info('they talk about bacon again!', [
                'user' => $this->security->getUser(),
            ]);
        }

        $item = $this->cache->getItem('markdown_'.md5($source));
        if (!$item->isHit()) {
            if ($this->isDebug) {
                return $this->markdown->transform($source);
            }
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}
