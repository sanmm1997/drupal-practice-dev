<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPListForm
 */

namespace Drupal\rsvplist\Form;

use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormStateInterface;

class RSVPListForm extends FormBase {
  /**
   *  (@inheritdoc)
   */  
  public function getFormId() {
    return 'rsvplist_email_form';
  }

  /**
   * (@inheritdoc)
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;

    $form['mail'] = [
      '#size' => 25,
      '#required' => TRUE,
      '#type' => 'textfield',
      '#title' => $this->t('Email Address'),
      '#description' => $this->t("We'll send updates to the email address your provide"),
    ];

    $form['nid'] = [
      '#value' => $nid,
      '#type' => 'hidden',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('RSVP')
    ];

    return $form;
  }

  /**
   * (@inheritdoc)
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $value = $form_state->getValue('mail');
    $validator = \Drupal::service('email.validator');

    if ($value == !$validator->isValid($value)) {
      $form_state->setErrorByName('mail', $this->t($validator->getError()->getMessage()));
      return;
    }

    /**
     * @var Node $node
     */
    $node = \Drupal::routeMatch()->getParameter('node');
    /** Check if email already is set for this node */

    $select = Database::getConnection()->select('rsvplist', 'r');
    $select->fields('r', ['nid']);

    $select->condition('mail', $value);
    $select->condition('nid', $node->id());

    $results = $select->execute();

    if (empty($results->fetchCol())) return;

    $form_state->setErrorByName('mail', t('The address %mail is already subscribed to this list', ['%mail', $value]));
  }

  /**
   * (@inheritdoc)
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $user = User::load(\Drupal::currentUser()->id());
    $conn = Database::getConnection();

    $data = [
      'created' => time(),
      'uid' => $user->id(),
      'nid' => $form_state->getValue('nid'),
      'mail' => $form_state->getValue('mail'),
    ];

    try {
      $conn->insert('rsvplist')
      ->fields($data)
      ->execute();

      $this->messenger()->addStatus('Thankyou for you RSVP, you are on the list for the event');
    } catch (\Exception $e) {
      $this->messenger()->addError($e->getMessage());
    }
  }
}