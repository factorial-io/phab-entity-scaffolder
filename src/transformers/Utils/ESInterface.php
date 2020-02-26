<?php

interface ESInterface {

    /**
     * Absolute path to the template.
     */
    public function getTemplateFile();

    /**
     * Path to template directory.
     */
    public function getTemplateDir();

    /**
     * Template file name.
     */
    public function getTemplateFileName();

    /**
     * Get the Drupal config name.
     */
    public function getConfigName();

    /**
     * Get list of all configurations.
     */
    public function getConfigurations();

    /**
     * Massage the template in php world.
     */
    public function getTemplateOverrideData();
}