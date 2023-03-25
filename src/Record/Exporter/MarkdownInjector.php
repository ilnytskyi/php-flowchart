<?php

declare(strict_types=1);

namespace Flowchart\Record\Exporter;

class MarkdownInjector implements ExporterInterface
{
    private const REGEX_PATTER = '/^\s*?```mermaid\s?$\n(?<chartBody>%%\[namespace\](.*\n?)*)\s?```/im';

    public function __construct(
        protected readonly RawString $rawStringExporter
    ) {
    }

    public function export(array $records, array $chartConfig, ?string $destinationPath = null): string
    {
        $newChart = $this->rawStringExporter->export($records, $chartConfig);

        $configDir = $chartConfig['config_dir'];
        $namespace = $chartConfig['namespace'];
        $namespaceLine = '%%' . sprintf('[%s]', $chartConfig['namespace']);
        $namespaceRegex = str_replace('namespace', $namespace, self::REGEX_PATTER);

        $targetMdFile = sprintf(
            '%s/%s',
            rtrim($configDir, DIRECTORY_SEPARATOR),
            $destinationPath
        );

        $markdownContent = file_get_contents($targetMdFile);

        $currentContents = preg_match_all($namespaceRegex, $markdownContent, $matches);
        $oldChart = $matches['chartBody'][0] ?? null;

        if(!$currentContents || !$oldChart) {
            return sprintf('Chart "%s", not found in "%s"', $namespace, $targetMdFile);
        }

        $newChart = $namespaceLine . PHP_EOL . $newChart;
        $newContents = str_replace($oldChart, $newChart, $markdownContent);

        file_put_contents($targetMdFile, $newContents);

        return sprintf('Updated "%s"', $targetMdFile);
    }
}
