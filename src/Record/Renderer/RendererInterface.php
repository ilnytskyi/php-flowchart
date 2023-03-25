<?php

declare(strict_types=1);

namespace Flowchart\Record\Renderer;

use Flowchart\Record\RecordInterface;

interface RendererInterface
{
    public function render(RecordInterface $record): string;
}