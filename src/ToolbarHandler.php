<?php

namespace Drupal\nireneko;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Toolbar integration handler.
 *
 * Esta clase se encarga de preparar el array que creara el toolbar.
 *
 * https://nireneko.com/articulo/crear-menu-toolbar-drupal-8
 */
class ToolbarHandler implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuLinkTree;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * ToolbarHandler constructor.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_link_tree
   *   The menu link tree service.
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The current user.
   */
  public function __construct(MenuLinkTreeInterface $menu_link_tree, AccountProxyInterface $account) {
    $this->menuLinkTree = $menu_link_tree;
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('toolbar.menu_tree'),
      $container->get('current_user')
    );
  }

  /**
   * Hook bridge.
   *
   * @return array
   *   The nireneko toolbar items render array.
   *
   * @see hook_toolbar()
   */
  public function toolbar() {
    $items['nireneko'] = [
      '#cache' => [
        'contexts' => ['user.permissions'],
      ],
    ];

    if ($this->account->hasPermission('access nireneko administration pages')) {

      $items['nireneko'] = [
        '#type' => 'toolbar_item',
        'tab' => [
          '#type' => 'link',
          '#title' => $this->t('Nireneko'),
          '#url' => Url::fromRoute('nireneko.admin'), //Ruta base del toolbar
          '#attributes' => [
            'title' => $this->t('Nireneko'),
            'class' => [
              'toolbar-icon',
              'toolbar-icon-nireneko',
            ],
            'data-drupal-subtrees' => '',
            'data-toolbar-escape-admin' => TRUE,
          ],
        ],
        'tray' => [
          '#heading' => $this->t('@menu_label actions', ['@menu_label' => $this->t('Nireneko')]),
          'nireneko_menu' => [
            '#type' => 'container',
            '#id' => 'nireneko',
            '#pre_render' => ['nireneko_prerender_toolbar_tray'], //A esta funcion llamaremos para crear el menu, esta en nireneko.module
            '#attributes' => [
              'class' => ['toolbar-menu-administration'],
            ],
          ],
        ],
        '#weight' => 999,
        '#attached' => [
          'library' => [
            'nireneko/toolbar', //La libreria en la cual añadiremos el css para añadir el icono y js para que sea desplegable.
          ],
        ],
      ];
    }

    return $items;
  }

}
