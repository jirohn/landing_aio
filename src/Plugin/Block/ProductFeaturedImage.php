<?php

namespace Drupal\landing_aio\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'ProductFeaturedImage' block.
 *
 * @Block(
 *   id = "product_featured_image",
 *   admin_label = @Translation("Producto destacado"),
 *   category = @Translation("Landing AIO")
 * )
 */
class ProductFeaturedImage extends BlockBase {

 /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    $title = $config['title'] ?? 'Producto destacado';
    $image_fid = $config['image'][0] ?? NULL;
    $image_url = NULL;
    if ($image_fid) {
      $image_file = \Drupal\file\Entity\File::load($image_fid);
      $image_url = $image_file->createFileUrl();
    }
    $description = $config['description'] ?? 'Descripción del producto destacado';
    $background_color = $config['background_color'] ?? '#F0F0F0';
    $link = $config['link'] ?? '';
    return [
      '#theme' => 'landing_aio_featured_product',
      '#title' => $title,
      '#image_url' => $image_url,
      '#description' => $description,
      '#background_color' => $background_color,
      '#link' => $link,
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Título'),
      '#default_value' => $config['title'] ?? '',
    ];

    $form['image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Imagen'),
      '#upload_location' => 'public://featured_product_images/',
      '#default_value' => $config['image'] ?? '',
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg jpeg png gif svg'],
      ],
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Descripción'),
      '#default_value' => $config['description'] ?? '',
    ];
    $form['link'] = [
      '#type' => 'url',
      '#title' => $this->t('Link'),
      '#description' => $this->t('Enter the link for the "IR A LA TIENDA" button.'),
      '#default_value' => $config['link'] ?? '',
    ];
    $form['background_color'] = [
      '#type' => 'color',
      '#title' => $this->t('Color de fondo'),
      '#default_value' => $config['background_color'] ?? '#F0F0F0',
    ];

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $this->setConfigurationValue('title', $form_state->getValue('title'));

    $image = $form_state->getValue('image');
    if (!empty($image)) {
      $file = \Drupal\file\Entity\File::load($image[0]);
      $file->setPermanent();
      $file->save();

      // Set the file usage for this block.
      \Drupal::service('file.usage')->add($file, 'landing_aio', 'featured_product_block', $this->getPluginId());

      $this->setConfigurationValue('image', $image);
    }

    $this->setConfigurationValue('description', $form_state->getValue('description'));
    $this->setConfigurationValue('background_color', $form_state->getValue('background_color'));
    $this->setConfigurationValue('link', $form_state->getValue('link'));

  }


}
