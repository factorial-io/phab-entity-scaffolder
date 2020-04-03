<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class ResponsiveImageTest extends BaseScaffoldingTest
{
    private static $filenames = [
        'image.style.esimg_50x50.yml',
        'image.style.esimg_100x50.yml',
        'image.style.esimg_100x100.yml',
        'image.style.esimg_200w.yml',
        'image.style.esimg_200x100.yml',
        'image.style.esimg_400w.yml',
        'responsive_image.styles.popup.yml',
    ];


    public function testResponsiveImages()
    {

        $this->copyBaseline(
            'baseline/responsive_image/index',
            'results/responsive_image/index',
            self::$filenames
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
            self::$filenames,
            1
        );
    }
}
