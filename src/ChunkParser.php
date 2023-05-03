<?php

declare(strict_types=1);

namespace JsonStreamingParser;

use JsonStreamingParser\Listener\ListenerInterface;

class ChunkParser extends AbstractParser
{

    public function __construct(
      ListenerInterface $listener,
      bool $emitWhitespace = false
    ) {
        parent::__construct($listener, $emitWhitespace);

        $this->lineNumber = 1;
        $this->charNumber = 1;
    }

    public function parseChunk(string $chunk): void
    {
        $byteLen = \strlen($chunk);
        for ($i = 0; $i < $byteLen; $i++) {
            $this->consumeChar($chunk[$i]);
            $this->charNumber++;
        }

        // Register the lineNumber as not the first, so we don't
        // parse an UTF8 BOM twice.
        $this->lineNumber = -1;
    }

}
