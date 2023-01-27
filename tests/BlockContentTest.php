<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class BlockContentTest extends BaseScaffoldingTest
{

    public function testBlockContentScaffold()
    {

        $this->copyBaseline(
            'baseline/block_content/index',
            'results/block_content/index',
            []
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
            []
        );
    }
    
    public function testChangedConfiguration()
    {
        $this->copyBaseline(
            'baseline/block_content/index',
            'results/test_changed/index',
            []
        );
        $command = $this->application->find('scaffold');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'scaffold-path' => $this->getcwd().'/assets/block_content/test_changes.yml'
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Start scaffolding script', $output);
        $this->assertContains('Scaffolding finished successfully', $output);

        // Diff the arrays and throw exceptions if necessary.

        $view_config = $this->parseConfig(
            'results/test_changed/index/core.entity_view_display.block_content.card.default.yml'
        );

        $this->assertEquals('noindex', $view_config['content']['field_card_job_link']['settings']['rel']);
        $this->assertEquals('_top', $view_config['content']['field_card_job_link']['settings']['target']);

        $codesnippet_storage = $this->parseConfig(
            'results/test_changed/index/field.storage.block_content.field_card_codesnippet.yml'
        );

        $this->assertEquals('3', $codesnippet_storage['cardinality']);
    }
}
