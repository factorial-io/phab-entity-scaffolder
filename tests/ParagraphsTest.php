<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class ParagraphsTest extends BaseScaffoldingTest
{


    public function testParagraphsScaffold()
    {

        $this->copyBaseline(
            'baseline/paragraphs/index',
            'results/paragraphs/index',
            []
        );

        $command = $this->application->find('scaffold');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'scaffold-path' => $this->getcwd().'/assets/paragraphs/index.yml'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Start scaffolding script', $output);
        $this->assertContains('Scaffolding finished successfully', $output);

        // Diff the arrays and throw exceptions if necessary.

        $this->assertEqualContents(
            'baseline/paragraphs/index',
            'results/paragraphs/index',
            []
        );
    }
}
