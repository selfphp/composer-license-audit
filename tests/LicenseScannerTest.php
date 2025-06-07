<?php

use PHPUnit\Framework\TestCase;
use Selfphp\ComposerLicenseAudit\Audit\LicenseScanner;

class LicenseScannerTest extends TestCase
{
    private string $lockFile;

    protected function setUp(): void
    {
        $fixturesDir = __DIR__ . '/fixtures';
        if (!is_dir($fixturesDir)) {
            mkdir($fixturesDir, 0777, true);
        }

        $this->lockFile = $fixturesDir . '/composer.lock';

        $dummyLock = [
            'packages' => [
                [
                    'name' => 'vendor/package-one',
                    'version' => '1.0.0',
                    'license' => ['MIT']
                ],
                [
                    'name' => 'vendor/package-two',
                    'version' => '2.1.0',
                    'license' => ['GPL-3.0']
                ]
            ],
            'packages-dev' => [
                [
                    'name' => 'vendor/dev-package',
                    'version' => '0.3.1',
                    'license' => ['Apache-2.0']
                ]
            ]
        ];

        file_put_contents($this->lockFile, json_encode($dummyLock));
    }

    public function test_it_scans_packages_correctly()
    {
        $scanner = new LicenseScanner();
        $result = $scanner->scan($this->lockFile);

        $this->assertCount(3, $result);

        $this->assertEquals('vendor/package-one', $result[0]['name']);
        $this->assertEquals('MIT', $result[0]['license']);

        $this->assertEquals('vendor/package-two', $result[1]['name']);
        $this->assertEquals('GPL-3.0', $result[1]['license']);

        $this->assertEquals('vendor/dev-package', $result[2]['name']);
        $this->assertEquals('Apache-2.0', $result[2]['license']);
    }
}
