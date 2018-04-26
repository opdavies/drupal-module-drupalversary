<?php

namespace Drupal\drupalversary\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\drupalversary\Form\DrupalversaryBlockForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the Drupalversary block.
 *
 * @Block(
 *   id = "drupalversary_form_block",
 *   admin_label = @Translation("Drupalversary block"),
 *   category = @Translation("Forms")
 * )
 */
class DrupalversaryBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  private $formBuilder;

  public function __construct(array $configuration, $plugin_id, array $plugin_definition, FormBuilderInterface $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return $this->formBuilder->getForm(DrupalversaryBlockForm::class);
  }

}
