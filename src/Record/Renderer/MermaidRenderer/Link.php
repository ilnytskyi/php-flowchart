<?php

declare(strict_types=1);

namespace Flowchart\Record\Renderer\MermaidRenderer;

use Flowchart\Record\RecordInterface;

class Link
{
    public function render(RecordInterface $record): string
    {
        return sprintf(
            '%s--"%s"-->%s',
            $record->sourceId,
            $record->comment ?: ' ',
            $record->targetId
        );
    }
}