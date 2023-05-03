<?php

declare(strict_types=1);

namespace JsonStreamingParser\Test\Listener;

use JsonStreamingParser\AbstractParser;
use JsonStreamingParser\Listener\IdleListener;
use JsonStreamingParser\Listener\ParserAwareInterface;

class ParserAwareListener extends IdleListener implements ParserAwareInterface
{
    public $called = false;

    public function setParser(AbstractParser $parser): void
    {
        $this->called = true;
    }
}
