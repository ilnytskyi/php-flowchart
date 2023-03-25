<?php

declare(strict_types=1);

namespace Flowchart\Attribute;

use Flowchart\Record\RecordInterface;
use ReflectionAttribute;
use ReflectionClass;
use Flowchart\Record\Registry;

#[
    \Flowchart\Node('collector', 'Collector'),
    \Flowchart\Node('scannedDir', 'Scanned Directory'),
    \Flowchart\Link('collector', 'scannedDir', 'scans attributes'),
    \Flowchart\Link('scannedDir', 'collector', 'receives attributes'),
    \Flowchart\Link('collector', 'registry', 'register records')
]
class Collector
{
    public function __construct(
        protected readonly Registry $registry,
        protected readonly array $recordTypes = []
    ) {
    }

    public function collectFromClass(ReflectionClass $reflectionClass): void
    {
        foreach ($this->recordTypes as $recordType) {
            $declarationPath = $reflectionClass->getFileName();
            $this->collectAttributes($reflectionClass->getAttributes($recordType), $declarationPath);

            foreach ($reflectionClass->getMethods() as $method) {
                $this->collectAttributes($method->getAttributes($recordType), $declarationPath);
            }
        }
    }

    public function getTotalRecordsCount(): int
    {
        return $this->registry->getRecordsCount();
    }

    public function getTotalNamespacesCount(): int
    {
        return $this->registry->getNamespacesCount();
    }

    /**
     * @param ReflectionAttribute $attributes
     * @param string $declarationPath
     * @return void
     */
    protected function collectAttributes(array $attributes, string $declarationPath): void
    {
        foreach ($attributes as $attribute) {
            /** @var RecordInterface $instance */
            $instance = $attribute->newInstance();
            $instance->setDeclarationPath($declarationPath);
            $this->registry->addRecord($instance);
        }
    }
}
