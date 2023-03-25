<?php

declare(strict_types=1);

namespace Flowchart\Console\Command;

use Flowchart\Attribute\Collector;
use Flowchart\Config\Parser;
use Flowchart\Record\ExporterComposite;
use ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

#[
    \Flowchart\Node('GenerateCommand', 'Generate Command'),
    \Flowchart\Link('GenerateCommand', 'collector', '1. calls collector'),
    \Flowchart\Link('GenerateCommand', 'exporter', '2. calls exporter')
]
class GenerateCommand extends Command
{
    public const SUCCESS = 0;
    public const ERROR = 1;

    private const ARG_DIRECTORY = 'directory';
    private const EXCLUDE_DEFAULT = ['vendor'];
    private const PATTERNS = ['*.php', '.*flowchart.json'];
    private const NAME = 'generate';

    public function __construct(
        protected readonly Collector $collector,
        protected readonly ExporterComposite $exporterComposite,
        protected readonly Parser $configParser,
        string $name = self::NAME,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Generates all charts for provided directory')
            ->addArgument(
                self::ARG_DIRECTORY,
                InputArgument::REQUIRED,
                'Top directory of project/package to scan & update/generate charts'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $finder = $this->createFinder($input);

        $filesCount = $finder->count();
        $nodesCounter = 0;

        $output->writeln(sprintf("Found %s files for scan", $filesCount));

        $matchedFiles = [];
        $namespacesConfig = [];

        foreach ($finder as $file) {
            if (strtolower($file->getExtension()) === 'json') {
                $namespacesConfig[] = $file;
                continue;
            }
            require_once $file;
            $matchedFiles[$file->getRealPath()] = 1;
        }

        unset($finder);

        $classes = array_reverse(get_declared_classes());

        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);
            if (isset($matchedFiles[$reflectionClass->getFileName()])) {
                $this->collector->collectFromClass($reflectionClass);
                continue;
            }
            unset($reflectionClass);
        }

        unset($matchedFiles);
        unset($classes);

        $output->writeln(
            sprintf(
                'Found %s chart records in %s namespaces',
                $this->collector->getTotalRecordsCount(),
                $this->collector->getTotalNamespacesCount(),
            )
        );

        $this->exporterComposite->export(
            $this->configParser->parse($namespacesConfig, $input->getArgument(self::ARG_DIRECTORY))
        );

        return self::SUCCESS;
    }

    protected function createFinder(InputInterface $input): Finder
    {
        $dirs = array_filter([$input->getArgument(self::ARG_DIRECTORY)], 'is_dir');
        $exclude = []; //TODO: add later

        return (new Finder())
            ->files()
            ->in($dirs)
            ->name(self::PATTERNS)
            ->ignoreDotFiles(false)
            ->exclude(array_merge(self::EXCLUDE_DEFAULT, $exclude));
    }
}
