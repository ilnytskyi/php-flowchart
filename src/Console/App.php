<?php

declare(strict_types=1);

namespace Flowchart\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;

class App extends Application
{
    public const NAME = 'flowchart';
    public const VERSION = '0.0.1';

    public function __construct(
        string $name = self::NAME,
        string $version = self::VERSION,
        iterable $commands = []
    ) {
        parent::__construct($name, $version);

        foreach ($commands as $command) {
            $this->add($command);
        }
    }
    public function getLongVersion(): string
    {
        return trim(
            sprintf(
                '<info>%s</info> version <comment>%s</comment> by Stanislav Ilnytskyi',
                $this->getName(),
                $this->getVersion()
            )
        );
    }

    protected function getDefaultCommands(): array
    {
        return [new HelpCommand(), new ListCommand()];
    }
}