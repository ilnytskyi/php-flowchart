<?php

declare(strict_types=1);

namespace Flowchart\Record;

use Flowchart\Record\Renderer\RendererInterface;

class RendererProxy implements RendererInterface
{
    /**
     * @param RendererInterface[] $renderers
     * @param string $defaultRenderer
     */
    public function __construct(
        protected readonly array $renderers = [],
        protected readonly string $defaultRenderer
    ) {
    }

    public function render(RecordInterface $record): string
    {
        $renderer = $this->renderers[$this->defaultRenderer] ?? null;

        if (!$renderer) {
            throw new \RuntimeException(sprintf('Renderer "%s" not found', $this->defaultRenderer));
        }

        return $renderer->render($record);
    }
}