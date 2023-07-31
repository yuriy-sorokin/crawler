<?php
declare(strict_types=1);

namespace Crawler\Infrastructure\ConcurrencyLock;

class FileSystemConcurrencyLock implements ConcurrencyLockInterface
{
    private string $lockDirectoryPath;

    public function __construct(string $lockDirectoryPath)
    {
        $this->lockDirectoryPath = $lockDirectoryPath;

        if (false === \is_dir($this->lockDirectoryPath)) {
            \mkdir($this->lockDirectoryPath);
        }
    }

    public function acquireLock(string $key): void
    {
        $filePath = $this->getLockFilePath($key);

        $filePointer = @\fopen($filePath, 'x') or $this->throwException();

        if (false === $filePointer) {
            $this->throwException();
        }

        \fwrite($filePointer, '');
        \fclose($filePointer);
    }

    public function releaseLock(string $key): void
    {
        $filePath = $this->getLockFilePath($key);

        if (true === \file_exists($filePath)) {
            \unlink($filePath);
        }
    }

    private function getLockFilePath(string $key): string
    {
        return \sprintf('%s%s%s', $this->lockDirectoryPath, DIRECTORY_SEPARATOR, \sha1($key));
    }

    private function throwException()
    {
        throw new AlreadyLockedException();
    }
}
