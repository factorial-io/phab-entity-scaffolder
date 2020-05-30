<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class MediaTest extends BaseScaffoldingTest
{


    public function testParagraphsScaffold()
    {

        $this->copyBaseline(
            'baseline/media/index',
            'results/media/index',
            []
        );

        $command = $this->application->find('scaffold');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'scaffold-path' => $this->getcwd().'/assets/media/index.yml'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Start scaffolding script', $output);
        $this->assertContains('Scaffolding finished successfully', $output);

        // Diff the arrays and throw exceptions if necessary.

        $this->assertEqualContents(
            'baseline/media/index',
            'results/media/index',
            []
        );
    }
}
