<?php

namespace Phabalicious\Scaffolder\Tests;

use Phabalicious\Command\ScaffoldCommand;
use Phabalicious\Configuration\ConfigurationService;
use Phabalicious\Method\MethodFactory;
use Phabalicious\Method\ScriptMethod;
use Phabalicious\Utilities\Utilities;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Yaml\Yaml;

abstract class BaseScaffoldingTest extends TestCase
{

    protected $application;

    public function setUp()
    {
        $this->application = new Application();
        $this->application->setVersion(Utilities::FALLBACK_VERSION);
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $configuration = new ConfigurationService($this->application, $logger);
        $method_factory = new MethodFactory($configuration, $logger);
        $method_factory->addMethod(new ScriptMethod($logger));

        $this->application->add(new ScaffoldCommand($configuration, $method_factory));
    }

    protected function getcwd()
    {
        return getcwd().'/tests';
    }

    protected function assertEqualContents(
        $baseline_folder,
        $result_folder,
        array $filenames,
        $ignore_uuids_above_level = PHP_INT_MAX
    ) {
        if (empty($filenames)) {
            $filenames = $this->getFilesFromDirectory($baseline_folder, $filenames);
        }
        foreach ($filenames as $filename) {
            $a_path = $this->getcwd().'/'.$baseline_folder.'/'.$filename;
            $b_path = $this->getcwd().'/'.$result_folder.'/'.$filename;

            $a = Yaml::parseFile($a_path);
            $b = Yaml::parseFile($b_path);

            $this->removeUUIDs($a, $ignore_uuids_above_level);
            $this->removeUUIDs($b, $ignore_uuids_above_level);

            $this->assertArrayEquals($a, $b, $filename.' differs from baseline!');
            $this->assertArrayEquals($b, $a, $filename.' differs from baseline!');
        }
    }

    protected function removeUUIDs(&$array, $start_level = 0, $current_level = 0)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->removeUUIDs($array[$key], $start_level, $current_level + 1);
            } elseif (($current_level >= $start_level) && ($key == 'uuid')) {
                unset($array[$key]);
            }
        }
    }

    protected function copyBaseline(string $source, string $target, array $filenames)
    {
        if (!file_exists($this->getcwd() . '/' . $target)) {
            mkdir($this->getcwd() . '/' . $target, 0777, true);
        }
        if (empty($filenames)) {
            $filenames = $this->getFilesFromDirectory($source, $filenames);
        }
        foreach ($filenames as $filename) {
            file_put_contents(
                $this->getcwd() . '/' . $target . '/' . $filename,
                file_get_contents($this->getcwd() . '/' . $source . '/' . $filename)
            );
        }
    }

    protected function parseConfig(string $filename)
    {
        return Yaml::parseFile($this->getcwd() . '/' . $filename);
    }

    protected function assertArrayEquals(array $a, array $b, string $message, $rootPath = [])
    {
        foreach ($a as $key => $value) {
            $this->assertArrayHasKey($key, $b, $message);

            if (isset($b[$key])) {
                $keyPath = $rootPath;
                $keyPath[] = $key;

                if (is_array($value)) {
                    $this->assertArrayEquals($value, $b[$key], $message, $keyPath);
                } else {
                    $this->assertSame($value, $b[$key], "Failed asserting that `".$b[$key]
                        ."` matches expected `$value` for path `".implode(".", $keyPath)."`. " . $message);
                }
            }
        }
    }

    /**
     * @param  string  $source
     * @param  array  $filenames
     * @return array
     */
    protected function getFilesFromDirectory(string $source, array $filenames): array
    {
        $dir = scandir($this->getcwd().'/'.$source);
        foreach ($dir as $f) {
            if ($f[0] !== '.' && is_file($this->getcwd().'/'.$source.'/'.$f)) {
                $filenames[] = $f;
            }
        }
        return $filenames;
    }
}
