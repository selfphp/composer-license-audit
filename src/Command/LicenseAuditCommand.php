<?php

namespace Selfphp\ComposerLicenseAudit\Command;

use Selfphp\ComposerLicenseAudit\Audit\LicenseScanner;
use Selfphp\ComposerLicenseAudit\Audit\BlacklistChecker;
use Selfphp\ComposerLicenseAudit\Audit\ReportGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LicenseAuditCommand extends Command
{
    public function __construct()
    {
        parent::__construct('license:audit');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Scan composer.lock for license violations')
            ->addOption('lockfile', null, InputOption::VALUE_OPTIONAL, 'Path to composer.lock', 'composer.lock')
            ->addOption('blacklist', null, InputOption::VALUE_OPTIONAL, 'Path to blacklist.json', 'config/blacklist.json')
            ->addOption('json', null, InputOption::VALUE_OPTIONAL, 'Export result as JSON to given file')
            ->addOption('csv', null, InputOption::VALUE_OPTIONAL, 'Export result as CSV to given file')
            ->addOption('fail-on-blacklist', null, InputOption::VALUE_NONE, 'Exit with code 1 if any blacklisted license is found');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lockfile = $input->getOption('lockfile');
        $blacklistPath = $input->getOption('blacklist');
        $scanner = new LicenseScanner();
        $checker = new BlacklistChecker($blacklistPath);
        $report = new ReportGenerator();

        $packages = $scanner->scan($lockfile);

        $hasViolation = false;

        foreach ($packages as &$pkg) {
            $isBad = $checker->isForbidden($pkg['license'], $pkg['name']);
            $pkg['blacklisted'] = $isBad;

            $status = $isBad ? 'VIOLATION' : 'OK';

            $output->writeln(
                str_pad($pkg['name'], 30) .
                str_pad($pkg['license'], 25) .
                $status
            );

            if ($isBad) {
                $hasViolation = true;
            }
        }

        if ($path = $input->getOption('csv')) {
            $report->exportCsv($packages, $path);
            $output->writeln("CSV exported to: $path");
        }

        if ($path = $input->getOption('json')) {
            $report->exportJson($packages, $path);
            $output->writeln("JSON exported to: $path");
        }

        if ($hasViolation && $input->getOption('fail-on-blacklist')) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
