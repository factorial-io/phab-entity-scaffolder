<?php

namespace Phabalicious\Scaffolder\Tests;

use Symfony\Component\Console\Tester\CommandTester;

class NovasArkTest extends BaseScaffoldingTest
{
    private static $filenames = [
        // entity: block_content
        'block_content.type.novas_ark.yml',
        // form display: default
        'core.entity_form_display.block_content.novas_ark.default.yml',
        'core.entity_view_display.block_content.novas_ark.default.yml',
        // field: cta
        'field.field.block_content.novas_ark.field_novas_ark_cta.yml',
        'field.storage.block_content.field_novas_ark_cta.yml',
        // field: checkboxes
        'field.field.block_content.novas_ark.field_novas_ark_cbs.yml',
        'field.storage.block_content.field_novas_ark_cbs.yml',
        // field: image
        'field.field.block_content.novas_ark.field_novas_ark_image.yml',
        'field.storage.block_content.field_novas_ark_image.yml',
        // field: text
        'field.field.block_content.novas_ark.field_novas_ark_label.yml',
        'field.storage.block_content.field_novas_ark_label.yml',
        // field: textarea
        'field.field.block_content.novas_ark.field_novas_ark_body.yml',
        'field.storage.block_content.field_novas_ark_body.yml',
        // field: entity_reference : node
        'field.field.block_content.novas_ark.field_novas_ark_content.yml',
        'field.storage.block_content.field_novas_ark_content.yml',
        // field: entity_reference : taxonomy_term
        'field.storage.block_content.field_novas_ark_groups.yml',
        'field.field.block_content.novas_ark.field_novas_ark_groups.yml',
        // field: entity_reference : media
        'field.storage.block_content.field_novas_ark_medimg.yml',
        'field.field.block_content.novas_ark.field_novas_ark_medimg.yml',
    ];


    public function testNovasArk()
    {
        $this->copyBaseline(
            'baseline/novas_ark/index',
            'results/novas_ark/index',
            self::$filenames
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
            self::$filenames
        );
    }
}
