<?php

declare(strict_types=1);

namespace JsonStreamingParser\Test\Listener;

use JsonStreamingParser\AbstractParser;
use JsonStreamingParser\Listener\ParserAwareInterface;
use JsonStreamingParser\StoppableInterface;

class StopEarlyListener extends TestListener implements ParserAwareInterface
{

    /**
     * @var Parser;
     */
    protected $parser;

    public function setParser(AbstractParser $parser): void
    {
        if ($parser instanceof StoppableInterface) {
            $this->parser = $parser;
        }
    }

    public function startArray(): void
    {
        parent::startArray();
        $this->parser->stop();
    }
}
