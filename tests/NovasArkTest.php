<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class NovasArkTest extends BaseScaffoldingTest
{


    public function testNovasArk()
    {
        $this->copyBaseline(
            'baseline/novas_ark/index',
            'results/novas_ark/index',
            []
        );

        $command = $this->application->find('scaffold');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'scaffold-path' => $this->getcwd().'/assets/novas_ark/index.yml'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Start scaffolding script', $output);
        $this->assertContains('Scaffolding finished successfully', $output);

        // Diff the arrays and throw exceptions if necessary.

        $this->assertEqualContents(
            'baseline/novas_ark/index',
            'results/novas_ark/index',
            []
        );
    }
}
