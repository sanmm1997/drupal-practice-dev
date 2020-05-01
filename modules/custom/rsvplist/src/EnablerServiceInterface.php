<?php
/**
 * @File
 * Contains \Drupal\rsvplist\EnablerServiceInterface
 */

use Drupal\node\Entity\Node;

/**
  * Defines interface for EnablerService
  */
interface EnablerServiceInterface 
{
  /**
   * Set's a individual node to be RSVP enabled
   * 
   * @param Node $node
   */
  public function setEnabled(Node $node);

  /**
   * Deletes enabled settings for an individual node
   * 
   * @param Node $node
   */
  public function delEnabled(Node $node);

  /**
   * Check if an individual node is RSVP enabled
   * 
   * @param Node $node 
   * 
   * @return bool
   * Wheter the node is enabled for the RSVP functionallity
   */
  public function isEnabled(Node $node) : bool;
}