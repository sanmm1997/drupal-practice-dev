<?php
/**
 * @File
 * Contains \Drupal\rsvplist\Controller\ReportController
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Database\Database;
use Drupal\Core\Controller\ControllerBase;

/**
 * Defines controller for listing RSVPs submissions
 */
class ReportController extends ControllerBase 
{
  /**
   * Get all RSVPS for all nodes
   * 
   * @return array
   */
  protected function load() {
    $conn = Database::getConnection();
    $query = $conn->select('rsvplist', 'r');

    $query->join('users_field_data', 'u', 'r.uid = u.uid');
    $query->join('node_field_data', 'n', 'r.nid = n.nid');
    $query->addField('u', 'name', 'username');
    $query->addField('n', 'title');
    $query->addField('r', 'mail');
    
    $entries = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);

    return $entries;
  }

  /**
   * Crates the report page
   * 
   * @return array
   * Render array for report output
   */
  public function report() {
    $headers = [$this->t('Name'), $this->t('Event'), $this->t('Email')];

    foreach($entries = $this->load() as $entry) {
      $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
    }

    return [
      'table' => [
        '#type' => 'table',
        '#header' => $headers,
        '#rows' => isset($rows) ? $rows : [],
        '#empty' => $this->t('No entries available.'),
      ],
      'message' => [
        '#markup' => $this->t('Below is a list of all Events RSVPs including username, email and the name of evente')
      ],
      '#cache' => [
        'max-age' => 0
      ]
    ];
  }
}