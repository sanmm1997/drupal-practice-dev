<?php
/**
 * @File
 * Contains \Drupal\rsvplist\Form\RSVPListSettingsForm
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\ConfigFormBase;
use \Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form to configure RSVP List module settings
 */
class RSVPListSettingsForm extends ConfigFormBase 
{
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rsvplist_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [
      'rsvplist.settings'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $types = node_type_get_names();
    $config = $this->config('rsvplist.settings');

    $form['rsvplist_types'] = [
      '#options' => $types,
      '#type' => 'checkboxes',
      '#default_value' => $config->get('allowed_types'),
      '#title' => $this->t('The content types to enable RSVP collection for'),
    ];

    $form['array_filter'] = [
      '#type' => 'value',
      '#value' => TRUE
    ];
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $allowed_types = array_filter($form_state->getValue('rsvplist_types'));
    sort($allowed_types);

    $this->config('rsvplist.settings')
      ->set('allowed_types', $allowed_types)
      ->save();
    
    parent::submitForm($form, $form_state);
  }
}