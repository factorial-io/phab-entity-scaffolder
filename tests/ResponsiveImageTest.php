<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class ResponsiveImageTest extends BaseScaffoldingTest
{


    public function testResponsiveImages()
    {

        $this->copyBaseline(
            'baseline/responsive_image/index',
            'results/responsive_image/index',
            []
        );

        $command = $this->application->find('scaffold');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'scaffold-path' => $this->getcwd().'/assets/responsive_image/index.yml'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Start scaffolding script', $output);
        $this->assertContains('Scaffolding finished successfully', $output);

        // Diff the arrays and throw exceptions if necessary.

        $this->assertEqualContents(
            'baseline/responsive_image/index',
            'results/responsive_image/index',
            [],
            1
        );
    }
}
