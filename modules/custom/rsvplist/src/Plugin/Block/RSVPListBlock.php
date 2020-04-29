<?php
/**
 * @file
 * contains \Drupal\rsvplist\Plugin\Block\RSVPListBlock
 */

 namespace Drupal\rsvplist\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultForbidden;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides an 'RSVP List Block'
 * @Block(
 *  id = "rsvplist_block",
 *  admin_label = @Translation("RSVP List Block"),
 * )
 */
class RSVPListBlock extends BlockBase 
{
  /**
   * {@inheritdoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\rsvplist\Form\RSVPListForm');
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    /** @var \Drupal\node\Entity\Node $node */
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;

    return (is_numeric($nid)) 
      ? AccessResult::allowedIfHasPermission($account, 'view rsvplist') 
      : AccessResult::forbidden();
  }
}