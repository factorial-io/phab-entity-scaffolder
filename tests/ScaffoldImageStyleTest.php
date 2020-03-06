<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class ScaffoldImageStyleTest extends BaseScaffoldingTest
{
    private static $filenames = [
        'image.style.image_card__large__1x.yml',
        'image.style.image_card__large__2x.yml',
        'image.style.image_card__medium__1x.yml',
        'image.style.image_card__medium__2x.yml',
        'image.style.image_card__small__1x.yml',
        'image.style.image_card__small__2x.yml',
        'image.style.image_card__xsmall__1x.yml',
        'image.style.image_card__xsmall__2x.yml',
    ];


    public function testImageStyleScaffolding()
    {

        $command = $this->application->find('scaffold');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'scaffold-path' => $this->getcwd().'/assets/test-image-styles.yml'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Start scaffolding script', $output);
        $this->assertContains('Scaffolding finished successfully', $output);

        // Diff the arrays and throw exceptions if necessary.

        $this->assertEqualContents('baseline', 'results', self::$filenames);
    }

}

