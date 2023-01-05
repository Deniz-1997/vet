<?php

namespace App\Service\Logger;

use DateTimeImmutable;
use Exception;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;
use App\Packages\ValueObject\Logger\Data;

class File
{
    /**
     * @var string
     */
    private string $pathToLog;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(string $pathToLog, SerializerInterface $serializer) {
        $this->pathToLog = $pathToLog;
        $this->serializer = $serializer;
    }

    /**
     * @param Data $data
     * @param string $dir
     * @param string $fileName
     * @throws Exception
     */
    public function write(Data $data, string $dir, string $fileName): void
    {
        $handle = false;
        try {
            $pathToDir = $this->pathToLog . $dir;
            if (!is_dir($pathToDir)
                && !mkdir($pathToDir, 0775, true)
                && !is_dir($pathToDir)) {
                throw new RuntimeException(
                    sprintf('Directory "%s" was not created', $pathToDir)
                );
            }

            $pathToFile = sprintf(
                '%s/%s_%s.log',
                $pathToDir,
                $fileName,
                (new DateTimeImmutable())->format('Y-m-d')
            );

            if (!file_exists($pathToFile)) {
                $handle = fopen($pathToFile, 'wb');
            } else {
                $handle = fopen($pathToFile, 'ab');
            }

            if (false !== $handle) {
                fwrite(
                    $handle,
                    $this->serializer->serialize($data, 'json') . PHP_EOL
                );
            }
        } catch (Exception $e) {

        } finally {
            if ($handle) {
                fclose($handle);
            }
        }
    }
}
