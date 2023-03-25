<?php

declare(strict_types=1);

namespace Flowchart\Record\Exporter;

class NodePng implements ExporterInterface
{
    public function __construct(
        protected readonly TextFile $textFileExporter
    ) {
    }

    public function export(array $records, array $chartConfig, ?string $destinationPath = null): string
    {
//TODO: make mmdc executable configurable
//        $fullExecutable = shell_exec('whereis mmdc');
//        print_r($fullExecutable);

        $tmpMmd = $destinationPath . '.tmp';
        $exportedMmdTmp = rtrim($this->textFileExporter->export($records, $chartConfig, $tmpMmd));
        $configDir = $chartConfig['config_dir'];
        $namespace = $chartConfig['namespace'];

        $targetFile = sprintf(
            '%s/%s.png',
            rtrim($configDir, DIRECTORY_SEPARATOR),
            $destinationPath ?: $namespace
        );
//TODO: make puppeteerConfigFile.json accessible from root
        $puppeteerConfigJson = sprintf(
            '%s/../puppeteerConfigFile.json',
            $configDir
        );

        $command = sprintf(
            'mmdc -i %s -o %s -p %s',
            $exportedMmdTmp,
            $targetFile,
            $puppeteerConfigJson
        );

        exec($command, $output, $result);
        unlink($exportedMmdTmp);

        if (!file_exists($targetFile) || $result) {
            return 'error';
        }

        return $targetFile;
    }
}
