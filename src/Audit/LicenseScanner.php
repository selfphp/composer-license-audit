<?php

namespace Selfphp\ComposerLicenseAudit\Audit;

class LicenseScanner
{
    /**
     * Parses the composer.lock file and extracts license information
     *
     * @param string $lockFile Path to composer.lock
     * @return array List of packages with name, version, and license(s)
     */
    public function scan(string $lockFile): array
    {
        if (!file_exists($lockFile)) {
            throw new \RuntimeException("composer.lock not found: " . $lockFile);
        }

        $data = json_decode(file_get_contents($lockFile), true);

        $packages = array_merge(
            $data['packages'] ?? [],
            $data['packages-dev'] ?? []
        );

        $results = [];

        foreach ($packages as $pkg) {
            $results[] = [
                'name' => $pkg['name'] ?? '',
                'version' => $pkg['version'] ?? '',
                'license' => implode(', ', $pkg['license'] ?? ['unknown']),
            ];
        }

        return $results;
    }
}
