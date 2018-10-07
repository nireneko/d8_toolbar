<?php

namespace Drupal\nireneko\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\nireneko\NirenekoManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Nireneko routes.
 *
 * Esta clase es una copia de una interna del core para cargar los submenus
 * de los menus que no contienen una ruta especifica, podemos ver su uso en el
 * routing de la siguiente manera:
 *   _controller: '\Drupal\nireneko\Controller\NirenekoController::nirenekoAdminMenuBlockPage'
 */
class NirenekoController extends ControllerBase {

  /**
   * System Manager Service.
   *
   * @var \Drupal\nireneko\NirenekoManager
   */
  protected $nirenekoManager;

  /**
   * Constructs a new NirenekoController.
   *
   * @param \Drupal\nireneko\NirenekoManager $nirenekoManager
   *   System manager service.
   */
  public function __construct(NirenekoManager $nirenekoManager) {
    $this->nirenekoManager = $nirenekoManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('nireneko.manager')
    );
  }

  /**
   * Provides a single block from the administration menu as a page.
   */
  public function nirenekoAdminMenuBlockPage() {
    return $this->nirenekoManager->getBlockContents();
  }

}
