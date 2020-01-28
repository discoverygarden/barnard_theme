<?php
/**
 * Implements hook_form_system_theme_settings_alter().
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function barnard_theme_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL)  {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  // To avoid loosing the new theme setting 'background_file' from being cleared
  // we make sure to save it with our own form submit handler.
  $form['#submit'][] = 'barnard_theme_settings_form_submit';

  $form['barnard_theme_general'] = array(
    '#type' => 'fieldset',
    '#title' => t('NSCAD Theme Configuration'),
  );

  // Get all themes.
  $themes = list_themes();

  // Get the current theme.
  $active_theme = $GLOBALS['theme_key'];
  $form_state['build_info']['files'][] = str_replace("/$active_theme.info", '', $themes[$active_theme]->filename) . '/theme-settings.php';

  $form['barnard_theme_general']['body_background_image'] = array(
    '#type'     => 'managed_file',
    '#title'    => t('Front Page Background'),
    '#required' => FALSE,
    '#upload_location' => file_default_scheme() . '://theme/backgrounds/',
    '#default_value' => theme_get_setting('body_background_image'),
    '#upload_validators' => array(
      'file_validate_extensions' => array('gif png jpg jpeg'),
    ),
  );
}

/**
 * Implements hook_form_submit().
 */
function barnard_theme_settings_form_submit(&$form, &$form_state) {
  $image_fid = $form_state['values']['body_background_image'];
  $image = file_load($image_fid);

  if (is_object($image)) {
    // Check to make sure that the file is set to be permanent.
    if ($image->status == 0) {

      // Update the status.
      $image->status = FILE_STATUS_PERMANENT;

      // Save the update.
      file_save($image);

      // Add a reference to prevent warnings.
      file_usage_add($image, 'barnard_theme', 'theme', 1);
    }
  }
}
