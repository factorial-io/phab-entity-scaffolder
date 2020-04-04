<?php


namespace Drupal\pesh\Plugin\ListOptions;


use Drupal\list_predefined_options\Plugin\ListOptionsBase;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * @ListOptions(
 *   id = "example",
 *   label = @Translation("Example"),
 * )
 */
class Example extends ListOptionsBase {

  /**
   * {@inheritdoc}
   */
  public function getListOptions(FieldStorageDefinitionInterface $definition, FieldableEntityInterface $entity = NULL, &$cacheable = TRUE) {
    return [
      'a' => 'Option A',
      'b' => 'Option B',
      'c' => 'Option C',
      'd' => 'Option D',
    ];
  }

}
