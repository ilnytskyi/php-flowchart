<?php

declare(strict_types=1);

namespace Flowchart\Record;

use Flowchart\Record\RecordInterface;

#[\Flowchart\Node('registry', 'Records Registry')]
class Registry
{
    /**
     * @var RecordInterface[][]
     */
    private array $registry = [];

    public function addRecord(RecordInterface $record): void
    {
        $this->registry[$record->getNamespace()][] = $record;
    }

    public function getNamespacesCount(): int
    {
        return count($this->registry);
    }

    public function getRecordsCount(?string $namespace = null): int
    {
        if (isset($this->registry[$namespace])) {
            return count($this->registry[$namespace]);
        }

        return count($this->registry, COUNT_RECURSIVE);
    }

    public function getRecords(?string $namespace = null): array
    {
        return $this->registry[$namespace] ?? [];
    }
}