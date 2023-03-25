<?php

declare(strict_types=1);

namespace Flowchart;

use Attribute;
use Flowchart\Design\Link\Type;
use Flowchart\Record\RecordInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Link implements RecordInterface
{
    private string $declarationPath;

    public function __construct(
        public readonly string $sourceId,
        public readonly string $targetId,
        public readonly string $comment,
        public readonly ?string $namespace = null,
        public readonly string $type = Type::ARROW,
    ) {
    }

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