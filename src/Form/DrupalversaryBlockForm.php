<?php

namespace Drupal\drupalversary\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Generates the Drupalversary form.
 */
class DrupalversaryBlockForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'drupalversary_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
      '#name' => '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');

    if (is_numeric($username)) {
      $username = \Drupal::service('drupalversary.account_retriever')
        ->byUid($username)
        ->get('name');
    }

    $form_state->setRedirect('drupalversary.username', compact('username'));
  }

}
