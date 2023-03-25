<?php

declare(strict_types=1);

namespace Flowchart\Record;

interface RecordInterface
{
    public function getNamespace(): ?string;

    public function setDeclarationPath(string $path): void;

    public function getDeclarationPath(): string;
}