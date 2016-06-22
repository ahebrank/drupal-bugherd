<?php

/**
 * @file
 * Contains \Drupal\bugherd\Form\ConfigurationForm.
 */

namespace Drupal\bugherd\Form;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Configure site information settings for this site.
 */
class ConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'bugherd_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bugherd.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('bugherd.settings');

    $link = \Drupal::l('https://www.bugherd.com/', Url::fromUri('https://www.bugherd.com/'));
    $description = t('To obtain an API key sign up for BugHerd at @link.', array('@link' => $link));
    $form['api_key'] = array(
      '#type' => 'textfield',
      '#title' => t('BugHerd API key'),
      '#default_value' => $config->get('api_key'),
      '#description' => $description,
      '#size' => 60,
      '#required' => TRUE
    );
    $form['disable_on_admin'] = array(
      '#type' => 'checkbox',
      '#title' => t('Disable on admin pages'),
      '#default_value' => $config->get('disable_on_admin', FALSE),
      '#description' => t('Ticking the checkbox will prevent the BugHerd button being available on admin pages'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('bugherd.settings')
      ->set('api_key', $values['api_key'])
      ->set('disable_on_admin', $values['disable_on_admin'])
      ->save();
  }

}
