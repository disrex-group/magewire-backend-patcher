<?php
/**
 * Disrex V.O.F.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Disrex.nl license that is
 * available through the world-wide-web at this URL:
 * https://www.disrex.nl/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @author Disrex V.O.F
 * @category Modules
 * @copyright Copyright (c) Disrex V.O.F. (https://www.disrex.nl, support@disrex.nl)
 * @year 2025
 */

namespace Composer\MagewirePatchPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        // Nothing needed on activate for now
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // Optional cleanup logic, not needed here
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Optional uninstall logic, not needed here
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'post-install-cmd' => 'applyMagewirePatches',
            'post-update-cmd' => 'applyMagewirePatches'
        ];
    }

    public function applyMagewirePatches(Event $event): void
    {
        $io = $event->getIO();
        $rootDir = getcwd();
        $targetPath = $rootDir . '/vendor/magewirephp/magewire';

        if (!is_dir($targetPath)) {
            $io->writeError("<error>❌ magewirephp/magewire not found at: $targetPath</error>");
            return;
        }

        // List of all patches to apply
        $patches = [
            'magewire-backend.patch',
            'magewire-backend-2.patch',
        ];

        foreach ($patches as $patchFile) {
            $patchPath = $rootDir . "/vendor/disrex/magewire-backend-patcher/patches/$patchFile";

            if (!file_exists($patchPath)) {
                $io->writeError("<error>❌ Patch file not found: $patchPath</error>");
                continue;
            }

            // For the file-moving patch, skip if already done
            if ($patchFile === 'magewire-backend-2.patch') {
                $newFilePath = $targetPath . '/src/view/base/templates/component/exception.phtml';
                $oldFilePath = $targetPath . '/src/view/frontend/templates/component/exception.phtml';
                
                if (file_exists($newFilePath) && !file_exists($oldFilePath)) {
                    continue;
                }
            }

            // Check if patch command is available
            exec('patch --version 2>&1', $patchVersionOutput, $patchVersionExitCode);
            if ($patchVersionExitCode !== 0) {
                $io->writeError("<error>❌ The 'patch' command is not available on your system.</error>");
                return;
            }

            // Different systems have different patch versions with different options
            $dryRunOption = '--dry-run';
            foreach ($patchVersionOutput as $line) {
                // GNU patch supports --dry-run, but some versions don't
                if (strpos($line, 'GNU patch') === false) {
                    // Fallback for non-GNU patch (like on some macOS/BSD versions)
                    $dryRunOption = '--check';
                    break;
                }
            }

            // First run a dry-run to check if the patch needs to be applied
            $dryRunCmd = "patch {$dryRunOption} -p1 -d " . escapeshellarg($targetPath) . " < " . escapeshellarg($patchPath);
            exec($dryRunCmd . " 2>&1", $dryRunOutput, $dryRunExitCode);
            
            // Check if patch is already applied
            $alreadyApplied = false;
            foreach ($dryRunOutput as $line) {
                if (strpos($line, 'already applied') !== false || 
                    strpos($line, 'skipping patch') !== false ||
                    strpos($line, 'Reversed (or previously applied)') !== false) {
                    $alreadyApplied = true;
                    break;
                }
            }
            
            if ($alreadyApplied) {
                continue;
            }
            
            // Only show applying message and apply patch if not already applied
            $io->write("<info>⚙️ Applying patch: $patchFile...</info>");

            $cmd = "patch -p1 -d " . escapeshellarg($targetPath) . " < " . escapeshellarg($patchPath);
            exec($cmd . " 2>&1", $output, $exitCode);

            // Only show success message if patch actually made changes
            $changesApplied = false;
            if ($exitCode === 0) {
                foreach ($output as $line) {
                    if (strpos($line, 'patching file') !== false) {
                        $changesApplied = true;
                        break;
                    }
                }
                
                if ($changesApplied) {
                    $io->write("<info>✅ Successfully applied: $patchFile</info>");
                }
            } else {
                // Show error messages if the patch failed (not due to already being applied)
                $alreadyApplied = false;
                foreach ($output as $line) {
                    if (strpos($line, 'already applied') !== false || 
                        strpos($line, 'skipping patch') !== false ||
                        strpos($line, 'Reversed (or previously applied)') !== false) {
                        $alreadyApplied = true;
                        break;
                    }
                }
                
                if (!$alreadyApplied) {
                    $io->writeError("<error>❌ Failed to apply patch '$patchFile':</error>");
                    foreach ($output as $line) {
                        $io->writeError("  $line");
                    }
                }
            }
        }
    }
}
