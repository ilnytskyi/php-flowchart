<?php

declare(strict_types=1);

namespace Flowchart\Record\Renderer\MermaidRenderer;

use Flowchart\Record\RecordInterface;

class Node
{
    public function render(RecordInterface $record): string
    {
        return sprintf(
            '%s["%s"]',
            $record->id,
            $record->description
        );
    }
}