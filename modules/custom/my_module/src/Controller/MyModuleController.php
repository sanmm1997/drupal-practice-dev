<?php
/**
 * @file
 * contains \Drupal\my_module\Controller\MyModuleController.
 */
namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class MyModuleController extends ControllerBase {
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => t('This is my menu linked custom page'),
    ];
  }
}