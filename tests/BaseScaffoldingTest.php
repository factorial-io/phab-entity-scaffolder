<?php

namespace Phabalicious\Scaffolder\Tests;

use Phabalicious\Command\ScaffoldCommand;
use Phabalicious\Configuration\ConfigurationService;
use Phabalicious\Method\MethodFactory;
use Phabalicious\Method\ScriptMethod;
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
        $this->application->setVersion('3.4.0');
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

    protected function assertEqualContents($baseline_folder, $result_folder, array $filenames)
    {
        foreach ($filenames as $filename) {
            $a_path = $this->getcwd().'/'.$baseline_folder.'/'.$filename;
            $b_path = $this->getcwd().'/'.$result_folder.'/'.$filename;

            $a = Yaml::parseFile($a_path);
            $b = Yaml::parseFile($b_path);

            $this->removeUUIDs($a);
            $this->removeUUIDs($b);

            $this->assertEqualsCanonicalizing($a, $b, $filename.' differs from baseline!');
        }
    }

    protected function removeUUIDs(&$array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->removeUUIDs($array[$key]);
            } elseif ($key == 'uuid') {
                unset($array[$key]);
            }
        }
    }
}
