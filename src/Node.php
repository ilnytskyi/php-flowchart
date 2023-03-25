<?php

declare(strict_types=1);

namespace Flowchart;

use Attribute;
use Flowchart\Design\Node\Shape;
use Flowchart\Record\RecordInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Node implements RecordInterface
{
    private string $declarationPath;

    public function __construct(
        public readonly string $id,
        public readonly string $description,
        public readonly ?string $namespace = null,
        public readonly string $shape = Shape::BOX,
    ) {}

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setDeclarationPath(string $path): void
    {
        $this->declarationPath = $path;
    }

    public function getDeclarationPath(): string
    {
        return $this->declarationPath;
    }
}
