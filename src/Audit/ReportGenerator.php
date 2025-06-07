<?php

namespace Selfphp\ComposerLicenseAudit\Audit;

class ReportGenerator
{
    /**
     * Exports the license data to a CSV file
     *
     * @param array $packages Array of package info
     * @param string $path Destination file path
     */
    public function exportCsv(array $packages, string $path): void
    {
        $fp = fopen($path, 'w');

        // Add new column "Blacklisted"
        fputcsv($fp, ['Name', 'Version', 'License', 'Blacklisted']);

        foreach ($packages as $pkg) {
            $blacklisted = $pkg['blacklisted'] ?? false;
            fputcsv($fp, [
                $pkg['name'],
                $pkg['version'],
                $pkg['license'],
                $blacklisted ? 'true' : 'false'
            ]);
        }

        fclose($fp);
    }


    /**
     * Exports the license data to a JSON file
     *
     * @param array $packages Array of package info
     * @param string $path Destination file path
     */
    public function exportJson(array $packages, string $path): void
    {
        file_put_contents($path, json_encode($packages, JSON_PRETTY_PRINT));
    }
}
