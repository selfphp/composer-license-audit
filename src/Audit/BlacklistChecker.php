<?php

namespace Selfphp\ComposerLicenseAudit\Audit;

class BlacklistChecker
{
    /**
     * @var array<string> List of forbidden license identifiers (lowercase)
     */
    private array $blacklist = [];

    /**
     * @var array<string> List of allowed package names (lowercase)
     */
    private array $exceptions = [];

    /**
     * Constructor: loads blacklist and optional exception list
     *
     * @param string $blacklistPath Path to blacklist.json (contains "forbidden": [...])
     * @param string|null $exceptionPath Optional path to allowed-packages.json (contains "exceptions": [...])
     */
    public function __construct(string $blacklistPath, ?string $exceptionPath = null)
    {
        if (!file_exists($blacklistPath)) {
            throw new \RuntimeException("Blacklist file not found: " . $blacklistPath);
        }

        $data = json_decode(file_get_contents($blacklistPath), true);
        $this->blacklist = array_map('strtolower', $data['forbidden'] ?? []);

        if ($exceptionPath && file_exists($exceptionPath)) {
            $exceptionData = json_decode(file_get_contents($exceptionPath), true);
            $this->exceptions = array_map('strtolower', $exceptionData['exceptions'] ?? []);
        }
    }

    /**
     * Checks whether a given license is forbidden for a given package
     *
     * @param string $license License string (e.g. "MIT, GPL-3.0-only")
     * @param string $packageName Name of the Composer package (e.g. "vendor/package")
     * @return bool true if license is forbidden and package is not listed as exception
     */
    public function isForbidden(string $license, string $packageName): bool
    {
        $packageName = strtolower($packageName);

        // Check if package is explicitly allowed
        if (in_array($packageName, $this->exceptions, true)) {
            return false;
        }

        // Split multiple licenses
        $allLicenses = array_map('trim', explode(',', strtolower($license)));

        foreach ($allLicenses as $entry) {
            if (in_array($entry, $this->blacklist, true)) {
                return true;
            }
        }

        return false;
    }
}
