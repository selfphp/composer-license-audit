<?php

use PHPUnit\Framework\TestCase;
use Selfphp\ComposerLicenseAudit\Audit\BlacklistChecker;

class BlacklistCheckerTest extends TestCase
{
    private string $blacklistFile;
    private string $exceptionsFile;

    protected function setUp(): void
    {
        $fixturesDir = __DIR__ . '/fixtures';

        if (!is_dir($fixturesDir)) {
            mkdir($fixturesDir, 0777, true);
        }

        $this->blacklistFile = $fixturesDir . '/blacklist.json';
        $this->exceptionsFile = $fixturesDir . '/allowed-packages.json';

        file_put_contents($this->blacklistFile, json_encode([
            'forbidden' => [
                'gpl-3.0',
                'agpl-3.0'
            ]
        ]));

        file_put_contents($this->exceptionsFile, json_encode([
            'exceptions' => [
                'allowed/package'
            ]
        ]));
    }

    public function test_forbidden_license_is_detected()
    {
        $checker = new BlacklistChecker($this->blacklistFile);
        $this->assertTrue($checker->isForbidden('GPL-3.0', 'some/package'));
    }

    public function test_allowed_package_ignores_forbidden_license()
    {
        $checker = new BlacklistChecker($this->blacklistFile, $this->exceptionsFile);
        $this->assertFalse($checker->isForbidden('GPL-3.0', 'allowed/package'));
    }

    public function test_license_not_in_blacklist_is_not_forbidden()
    {
        $checker = new BlacklistChecker($this->blacklistFile);
        $this->assertFalse($checker->isForbidden('MIT', 'some/package'));
    }

    public function test_multiple_licenses_detects_violation()
    {
        $checker = new BlacklistChecker($this->blacklistFile);
        $this->assertTrue($checker->isForbidden('MIT, AGPL-3.0', 'some/package'));
    }

    public function test_multiple_licenses_passes_when_no_violation()
    {
        $checker = new BlacklistChecker($this->blacklistFile);
        $this->assertFalse($checker->isForbidden('MIT, Apache-2.0', 'some/package'));
    }
}
