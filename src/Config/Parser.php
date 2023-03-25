<?php

declare(strict_types=1);

namespace Flowchart\Config;

use Symfony\Component\Finder\SplFileInfo;

class Parser
{
    /**
     * @param SplFileInfo $configFiles
     * @param string $defaultDir
     * @return array
     */
    public function parse(array $configFiles, string $defaultDir): array
    {
        $namespacesConfig = [];
        $workingDir = getcwd();
        $rootDirPath = realpath(
            sprintf(
                '%s/%s',
                rtrim($workingDir, DIRECTORY_SEPARATOR),
                $defaultDir
            )
        );

        foreach ($configFiles as $file) {
            $jsonConfig = json_decode($file->getContents(), true);
            $jsonConfig['config_dir'] = realpath(pathinfo($file->getRealPath())['dirname']);
            $jsonConfig['root_dir'] = $rootDirPath;

            $namespacesConfig[$jsonConfig['namespace'] ?? null] = $jsonConfig;
        }

        return $namespacesConfig;
    }
}