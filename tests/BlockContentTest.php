<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class BlockContentTest extends BaseScaffoldingTest
{
    private static $filenames = [
        'block_content.type.card.yml',
        'core.entity_form_display.block_content.card.default.yml',
        'field.field.block_content.card.field_card_author.yml',
        'field.field.block_content.card.field_card_job_title.yml',
        'field.field.block_content.card.field_card_label.yml',
        'field.storage.block_content.field_card_author.yml',
        'field.storage.block_content.field_card_job_title.yml',
        'field.storage.block_content.field_card_label.yml'
    ];


    public function testBlockContentScaffold()
    {

        $this->copyBaseline(
            'baseline/block_content/index',
            'results/block_content/index',
            self::$filenames
        );
        $command = $this->application->find('scaffold');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'scaffold-path' => $this->getcwd().'/assets/block_content/index.yml'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Start scaffolding script', $output);
        $this->assertContains('Scaffolding finished successfully', $output);

        // Diff the arrays and throw exceptions if necessary.

        $this->assertEqualContents(
            'baseline/block_content/index',
            'results/block_content/index',
            self::$filenames
        );
    }
}
