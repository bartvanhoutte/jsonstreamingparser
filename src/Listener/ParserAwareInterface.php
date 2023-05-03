<?php

declare(strict_types=1);

namespace JsonStreamingParser\Listener;

use JsonStreamingParser\AbstractParser;

interface ParserAwareInterface
{
    public function setParser(AbstractParser $parser): void;
}
