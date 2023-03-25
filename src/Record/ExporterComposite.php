<?php

declare(strict_types=1);

namespace Flowchart\Record;

use Flowchart\Record\Registry;

#[
    \Flowchart\Node('exporter', 'Exporter Composite'),
    \Flowchart\Link('exporter', 'registry', 'finds records by namespace')
]
class ExporterComposite
{
    public function __construct(
        protected readonly Registry $registry,
        protected readonly array $exporters = []
    ) {
    }

    public function export(array $namespacesConfigs): void
    {
        $rootNamespace = null;
        $exportedNamespaces = [];
        foreach ($namespacesConfigs as $config) {
            $namespace = $config['namespace'];

            if ($config['root_dir'] === $config['config_dir'] && $rootNamespace === null) {
                $rootNamespace = $namespace;
            }

            $records = $this->registry->getRecords($namespace);
            if (empty($records)) {
                continue;
            }
        }

        /**
         * If no namespaces specified in found records
         * Use default .flowchart.json config from root directory
         */
        if (empty($exportedNamespaces)) {
            $config = $namespacesConfigs[$rootNamespace];
            $records = $this->registry->getRecords();
        }
        foreach ($config['exports'] ?? ['default'] as $destination => $type) {
            $this->exporters[$type]->export($records, $config, (string)$destination ?: null);
        }
    }
}
