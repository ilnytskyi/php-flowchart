<?php

namespace Flowchart\Record\Exporter;

use Flowchart\Record\RecordInterface;

class TextFile
{
    public function __construct(
        protected readonly RawString $rawStringExporter
    ) {
    }

    /**
     * @param RecordInterface[] $records
     * @param array $chartConfig
     * @param string|null $destinationPath
     * @return string
     */
    public function export(array $records, array $chartConfig, ?string $destinationPath = null): string
    {
        $output = $this->rawStringExporter->export($records, $chartConfig);

        $configDir = $chartConfig['config_dir'];
        $namespace = $chartConfig['namespace'];

        $targetFile = sprintf(
            '%s/%s.mmd',
            rtrim($configDir, DIRECTORY_SEPARATOR),
            $destinationPath ?: $namespace
        );

        file_put_contents($targetFile, $output);

        return $targetFile;
    }
}