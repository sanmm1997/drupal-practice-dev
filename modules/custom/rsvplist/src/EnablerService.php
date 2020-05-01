<?php
/**
 * @File
 * Contains \Drupal\rsvplist\EnablerService
 */

namespace Drupal\rsvplist;

use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;

/**
 * Define a service for managing RSVP list enable for nodes
 */
class EnablerService 
{
  /**
   * {@inheritDoc}
   */
  public function setEnabled(Node $node) {
    if ($this->isEnabled($node)) return;

    $insert = Database::getConnection()->insert('rsvplist_enable');
    $insert->fields(['nid'], [$node->id()]);
    $insert->execute();
  }

  /**
   * {@inheritDoc}
   */
  public function isEnabled(Node $node) : bool {
    if ($node->isNew()) return false;

    $select = Database::getConnection()->select('rsvplist_enable', 're');
    $select->fields('re', ['nid']);
    $select->condition('nid', $node->id());

    $results = $select->execute();

    return !empty($results->fetchCol());
  }

  /**
   * {@inheritDoc}
   */
  public function delEnabled(Node $node) {
    $delete = Database::getConnection()->delete('rsvplist_enable');
    $delete->condition('nid', $node->id());

    $delete->execute();
  }
}