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

            $io->write("<info>⚙️ Applying patch: $patchFile...</info>");

            $cmd = "patch -p1 -d " . escapeshellarg($targetPath) . " < " . escapeshellarg($patchPath);
            exec($cmd, $output, $exitCode);

            if ($exitCode === 0) {
                $io->write("<info>✅ Successfully applied: $patchFile</info>");
            } else {
                $io->write("<comment>⚠️ Patch '$patchFile' may already be applied or failed. Please verify manually if needed.</comment>");
            }
        }
    }
}
