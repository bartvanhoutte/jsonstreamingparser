<?php

declare(strict_types=1);

namespace JsonStreamingParser;

use JsonStreamingParser\Listener\ListenerInterface;
use JsonStreamingParser\Listener\PositionAwareInterface;

class Parser extends AbstractParser implements StoppableInterface
{

    private $stream;

    private $stopParsing = false;

    public function __construct(
      $stream,
      ListenerInterface $listener,
      string $lineEnding = "\n",
      bool $emitWhitespace = false,
      int $bufferSize = 8192
    ) {
        parent::__construct($listener, $lineEnding, $emitWhitespace, $bufferSize);

        if (!\is_resource($stream) || 'stream' !== get_resource_type($stream)) {
            throw new \InvalidArgumentException('Invalid stream provided');
        }

        $this->stream = $stream;
    }

    public function parse(): void
    {
        $this->lineNumber = 1;
        $this->charNumber = 1;
        $eof = false;

        while (!feof($this->stream) && !$eof) {
            $pos = ftell($this->stream);
            // set the underlying streams chunk size, so it delivers according to the request from stream_get_line
            stream_set_chunk_size($this->stream, $this->bufferSize);
            $line = stream_get_line($this->stream, $this->bufferSize, $this->lineEnding);

            if (false === $line) {
                $line = '';
            }

            $ended = (bool) (ftell($this->stream) - \strlen($line) - $pos);
            // if we're still at the same place after stream_get_line, we're done
            $eof = ftell($this->stream) === $pos;

            $byteLen = \strlen($line);
            for ($i = 0; $i < $byteLen; $i++) {
                if ($this->listener instanceof PositionAwareInterface) {
                    $this->listener->setFilePosition($this->lineNumber, $this->charNumber);
                }
                $this->consumeChar($line[$i]);
                $this->charNumber++;

                if ($this->stopParsing) {
                    return;
                }
            }

            if ($ended) {
                $this->lineNumber++;
                $this->charNumber = 1;
            }
        }
    }

    public function stop(): void
    {
        $this->stopParsing = true;
    }

}
