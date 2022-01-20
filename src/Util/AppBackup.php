<?php 
namespace App\Util;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class AppBackup 
{
    protected $backupDir = '../__backups';

    public function create()
    {
        // Backup
        $dirstobackup = [
            '__storage',
            'bin',
            'config',
            'docs',
            'migrations',
            'public',
            'src',
            'templates',
            'translations',
        ];
        $filestobackup = [
            '.env',
            '.gitignore',
            'composer.json',
            'composer.lock',
            'README.md',
            'symfony.lock'
        ];

        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $dirname = 'FullBackup'. $now->format('YmdHis');
        $filesystem = new Filesystem();
        try {
            if (!file_exists($this->backupDir)) {
                $filesystem->mkdir($this->backupDir);
            } else {
                $finder = new Finder;
                $filesystem->remove($finder->files()->in($this->backupDir .'/'));
            }

            $filesystem->mkdir($this->backupDir .'/' . $dirname);
            foreach ($dirstobackup as $dir) {
                $filesystem->mirror('../'. $dir, $this->backupDir . '/' . $dirname . '/' . $dir);
            }
            foreach ($filestobackup as $file) {
                $filesystem->copy('../'. $file, $this->backupDir . '/' . $dirname . '/' . $file);
            }
            return true;
        } catch (IOExceptionInterface $exception) {
            throw new \Exception("An error occurred while creating backup ". $exception, 1);
        }
    }
}