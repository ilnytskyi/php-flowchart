<?php

declare(strict_types=1);

namespace Flowchart\Record\Exporter;

use Flowchart\Record\RecordInterface;
use Flowchart\Record\RendererProxy;

class RawString implements ExporterInterface
{
    public function __construct(
        protected readonly RendererProxy $rendererProxy
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
        /**
         * FIXME: hard dependency on Mermaid format, consider using header/footer decorators based on config
         * in case the app will be soo cool that other devs will want to support more graph formats
         */
        $output = "flowchart LR;\n";

        foreach ($records as $record) {
            $output .= $this->rendererProxy->render($record) . PHP_EOL;
        }

        return $output;
    }
}
