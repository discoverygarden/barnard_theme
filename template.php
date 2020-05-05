<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */

/**
 * Implements hook_preprocess_html().
 */
function barnard_theme_preprocess_html(&$variables) {
  if ($variables['is_front']) {
    $fid = theme_get_setting('body_background_image');
    if (!empty($fid) && isset(file_load($fid)->uri)) {
      $background_url = file_create_url(file_load($fid)->uri);
      $variables['background_style']['style'] = array(
        "background-image: url($background_url)",
      );
    }
    else {
      $bg_path = drupal_get_path('theme', 'barnard_theme') . "/images/nscad/NSCAD_WindowsAV.jpg";
      $bg_url = file_create_url($bg_path);
      $variables['background_style']['style'] = array(
        "background-image: url('" . $bg_url . "')",
      );
    }
  }
}

/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function barnard_theme_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  barnard_theme_preprocess_html($variables, $hook);
  barnard_theme_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
/* -- Delete this line if you want to use this function
function barnard_theme_preprocess_html(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  //$variables['classes_array'] = array_diff($variables['classes_array'], array('class-to-remove'));
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 */
function barnard_theme_preprocess_page(&$variables) {
  // XXX: If anything gets set up to make aliases for collections, this might
  // not work.
  $obj = menu_get_object('islandora_object', 2);
  $is_nscad_film_collection = FALSE;
  if ($obj && !empty($obj->relationships->get(NSCADDORA_RELS_URI, 'isCustomType', 'nscad_film_collection', RELS_TYPE_PLAIN_LITERAL))) {
    $is_nscad_film_collection = TRUE;
  }
  // If the page is for a custom NSCAD film collection, use the custom template.
  if ($is_nscad_film_collection) {
    $variables['theme_hook_suggestion'] = 'page__islandora__object__nscad_film_collection';
  }
}

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function
function barnard_theme_preprocess_node(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // Optionally, run node-type-specific preprocess functions, like
  // barnard_theme_preprocess_node_page() or barnard_theme_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $variables['node']->type;
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function barnard_theme_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function barnard_theme_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--sidebar.tpl.php template for sidebars.
  //if (strpos($variables['region'], 'sidebar_') === 0) {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('region__sidebar'));
  //}
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function barnard_theme_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  //if ($variables['block_html_id'] == 'block-system-main') {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('block__no_wrapper'));
  //}
}
// */

/**
 * Override views for NSCAD Film Collection displays.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 */
function barnard_theme_preprocess_views_view(&$variables) {
  if (($variables['name'] == 'nscad_film_collection_header') && (count($variables['view']->result) != 0)) {
    $vid_pid = $variables['view']->result[0]->PID;
    $video_object = array('object' => islandora_object_load($vid_pid));
    // Display the video object returned by the view.
    $variables['rows'] .= theme('islandora_video', $video_object);
  }
  elseif ($variables['name'] == 'nscad_film_collection_metadata') {
    module_load_include('inc', 'islandora', 'includes/metadata');
    $collection_obj = islandora_object_load($variables['view']->result[0]->PID);
    $variables['rows'] .= islandora_retrieve_description_markup($collection_obj);
    $variables['rows'] .= islandora_retrieve_metadata_markup($collection_obj);
  }
}
