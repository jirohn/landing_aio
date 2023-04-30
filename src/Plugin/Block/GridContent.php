<?php
namespace Drupal\landing_aio\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
/**
 * Provides a 'Grid Content' block.
 *
 * @Block(
 *   id = "landing_aio_grid_content",
 *   admin_label = @Translation("Grid Content"),
 * )
 */
class GridContent extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'grid_items' => [],
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['grid_items'] = [
      '#type' => 'details',
      '#title' => $this->t('Grid Items'),
      '#open' => TRUE,
    ];

    for ($i = 1; $i <= 4; $i++) {
      $form['grid_items']["item_$i"] = [
        '#type' => 'details',
        '#title' => $this->t('Grid Item %number', ['%number' => $i]),
      ];

      $form['grid_items']["item_$i"]['title'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Title'),
        '#default_value' => $this->configuration['grid_items']["item_$i"]['title'] ?? '',
      ];

      $form['grid_items']["item_$i"]['highlighted'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Highlighted'),
        '#default_value' => $this->configuration['grid_items']["item_$i"]['highlighted'] ?? FALSE,
      ];

      $form['grid_items']["item_$i"]['content_type'] = [
        '#type' => 'select',
        '#title' => $this->t('Content Type'),
        '#options' => [
          'texto' => $this->t('Text'),
          'imagen' => $this->t('Image'),
          'video' => $this->t('Video'),
        ],
        '#default_value' => $this->configuration['grid_items']["item_$i"]['content_type'] ?? 'texto',
      ];

      $form['grid_items']['item_' . $i]['content'] = [
        '#type' => 'text_format',
        '#title' => $this->t('Content'),
        '#format' => 'basic_html', // or any other format you want to use
        '#default_value' => isset($item['content']) ? $item['content'] : '',
        '#states' => [
          'visible' => [
            ':input[name="settings[grid_items][item_' . $i . '][content_type]"]' => ['value' => 'texto'],
          ],
        ],
      ];


      $form['grid_items']["item_$i"]['image'] = [
        '#type' => 'managed_file',
        '#title' => $this->t('Image'),
        '#upload_location' => 'public://landing_aio/images',
        '#upload_validators' => [
          'file_validate_extensions' => ['png jpg jpeg gif'],
        ],
        '#default_value' => $this->configuration['grid_items']["item_$i"]['image'] ?? '',
        '#states' => [
          'visible' => [
            ':input[name="settings[grid_items][item_' . $i . '][content_type]"]' => ['value' => 'imagen'],
          ],
        ],
      ];

      $form['grid_items']["item_$i"]['video'] = [
        '#type' => 'managed_file',
        '#title' => $this->t('Video'),
        '#upload_location' => 'public://landing_aio/videos',
        '#upload_validators' => [
          'file_validate_extensions' => ['mp4 webm'],
        ],
        '#default_value' => $this->configuration['grid_items']["item_$i"]['video'] ?? '',
        '#states' => [
          'visible' => [
            ':input[name="settings[grid_items][item_' . $i . '][content_type]"]' => ['value' => 'video'],
          ],
        ],
      ];
    }

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $grid_items = [];

    for ($i = 1; $i <= 4; $i++) {
      $item = [
        'content_type' => $form_state->getValue(['grid_items', "item_$i", 'content_type']),
        'title' => $form_state->getValue(['grid_items', "item_$i", 'title']),
        'highlighted' => $form_state->getValue(['grid_items', "item_$i", 'highlighted']),
        'image' => $form_state->getValue(['grid_items', "item_$i", 'image']),
        'video' => $form_state->getValue(['grid_items', "item_$i", 'video']),
      ];

      $content_array = $form_state->getValue(['grid_items', "item_$i", 'content']);
      $item['content'] = $content_array['value'];
      $item['content_format'] = $content_array['format'];

      $grid_items["item_$i"] = $item;
    }

    $this->configuration['grid_items'] = $grid_items;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $image = \Drupal\file\Entity\File::load($this->configuration['image']);
    if ($image) {
      $url = $image->createFileUrl($image->getFileUri());
      $build['product_highlight']['image'] = [
        '#theme' => 'image',
        '#uri' => $url,
        '#alt' => $this->configuration['title'],
      ];
    }

    $build['product_highlight']['title'] = [
      '#type' => 'markup',
      '#markup' => '<h2>' . $this->configuration['title'] . '</h2>',
    ];

    $build['product_highlight']['highlighted_text'] = [
      '#type' => 'markup',
      '#markup' => '<p>' . $this->configuration['highlighted_text'] . '</p>',
    ];

    $build['product_highlight']['cta_link'] = [
      '#type' => 'link',
      '#title' => $this->t('IR A LA TIENDA'),
      '#url' => \Drupal\Core\Url::fromUri($this->configuration['cta_link']),
      '#attributes' => [
        'class' => ['btn', 'btn-primary'],
      ],
    ];

    // Attach the CSS library.
    $build['#attached']['library'][] = 'landing_aio/product_highlight';

    return $build;
  }




}

