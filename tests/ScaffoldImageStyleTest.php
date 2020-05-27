<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class ScaffoldImageStyleTest extends BaseScaffoldingTest
{


    public function testImageStyleScaffolding()
    {
        $this->copyBaseline(
            'baseline/image_styles/index',
            'results/image_styles/index',
            []
        );

        $command = $this->application->find('scaffold');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'scaffold-path' => $this->getcwd().'/assets/test-image-styles.yml',
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Start scaffolding script', $output);
        $this->assertContains('Scaffolding finished successfully', $output);

        // Diff the arrays and throw exceptions if necessary.

        $this->assertEqualContents(
            'baseline/image_styles/index',
            'results/image_styles/index',
            []
        );
    }
}
