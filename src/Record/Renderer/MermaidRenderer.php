<?php

declare(strict_types=1);

namespace Flowchart\Record\Renderer;

use Flowchart\Link;
use Flowchart\Node;
use Flowchart\Record\RecordInterface;

class MermaidRenderer implements RendererInterface
{
    public const NAME = 'mermaid';

    public function __construct(
        protected readonly array $recordTypeRenderer = []
    ) {
    }

    public function render(RecordInterface $record): string
    {
        //TODO: make it more composable
        if ($record instanceof Node) {
            return $this->recordTypeRenderer['node']->render($record);
        }

        if ($record instanceof Link) {
            return $this->recordTypeRenderer['link']->render($record);
        }

        return '';
    }
}