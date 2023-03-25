<?php

declare(strict_types=1);

namespace Flowchart\Record\Exporter;

interface ExporterInterface
{
    public function export(array $records, array $chartConfig, ?string $destinationPath = null): string;
}