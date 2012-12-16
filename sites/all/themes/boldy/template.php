<?php

/**
 * Override of theme_breadcrumb().
 */
function boldy_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' › ', $breadcrumb) . '</div>';
    return $output;
  }
}

/**
 * Override or insert variables into the maintenance page template.
 */
function boldy_preprocess_maintenance_page(&$vars) {
  // While markup for normal pages is split into page.tpl.php and html.tpl.php,
  // the markup for the maintenance page is all in the single
  // maintenance-page.tpl.php template. So, to have what's done in
  // garland_preprocess_html() also happen on the maintenance page, it has to be
  // called here.
  boldy_preprocess_html($vars);
}

/**
 * Override or insert variables into the html template.
 */
function boldy_preprocess_html(&$vars) {
  // Add conditional CSS for IE6.
  drupal_add_css(path_to_theme() . '/fix-ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lt IE 7', '!IE' => FALSE), 'preprocess' => FALSE));

  // Change page title for homepage.
  if ($vars['is_front'] == TRUE) {
    $head_title = array('name' => check_plain(variable_get('site_name', 'Drupal')));
    if (variable_get('site_slogan', '')) {
      $head_title['slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
    }
    $vars['head_title_array'] = $head_title;
    $vars['head_title'] = implode(' | ', $head_title);
  }
}

/**
 * Override or insert variables into the html template.
 */
function boldy_process_html(&$vars) {
  // Hook into color.module
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
}

/**
 * Override or insert variables into the page template.
 */
function boldy_preprocess_page(&$vars) {
  // Move secondary tabs into a separate variable.
  $vars['tabs2'] = array(
    '#theme' => 'menu_local_tasks',
    '#secondary' => $vars['tabs']['#secondary'],
  );
  unset($vars['tabs']['#secondary']);

  if (isset($vars['main_menu'])) {
    $vars['primary_nav'] = theme('links__system_main_menu', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'main-menu'),
      ),
      'heading' => array(
        'text' => t('Main menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
        ));
  }
  else {
    $vars['primary_nav'] = FALSE;
  }
  if (isset($vars['secondary_menu'])) {
    $vars['secondary_nav'] = theme('links__system_secondary_menu', array(
      'links' => $vars['secondary_menu'],
      'attributes' => array(
        'class' => array('links', 'inline', 'secondary-menu'),
      ),
      'heading' => array(
        'text' => t('Secondary menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
        ));
  }
  else {
    $vars['secondary_nav'] = FALSE;
  }

  // Prepare header
  $site_fields = array();
  if (!empty($vars['site_name'])) {
    $site_fields[] = $vars['site_name'];
    $site_name_text = $vars['site_name'];
  }
  else {
    // If site_name is empty, rebuild it so the logo can have some alt text.
    $site_name_text = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if (!empty($vars['site_slogan'])) {
    $site_fields[] = $vars['site_slogan'];
    $slogan_text = $vars['site_slogan'];
  }
  else {
    // If site_slogan is empty, rebuild it so the logo can have some alt text.
    $slogan_text = filter_xss_admin(variable_get('site_slogan', ''));
  }
  $vars['site_title'] = implode(' ', $site_fields);
  if (!empty($site_fields)) {
    $site_fields[0] = '<span>' . $site_fields[0] . '</span>';
  }
  else {
    $vars['hidden_site_name'] = $site_name_text;
    $vars['hidden_slogan'] = $slogan_text;
  }
  $vars['site_html'] = implode(' ', $site_fields);

  // Set a variable for the site name title and logo alt attributes text.
  $vars['site_name_and_slogan'] = implode(' ', array($site_name_text, $slogan_text));
}

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function boldy_preprocess_node(&$variables, $hook) {
  // Add $unpublished variable.
  $variables['unpublished'] = (!$variables['status']) ? TRUE : FALSE;

  // Add a class for the view mode.
  if (!$variables['teaser']) {
    $variables['classes_array'][] = 'view-mode-' . $variables['view_mode'];
  }

  // Add a class to show node is authored by current user.
  if ($variables['uid'] && $variables['uid'] == $GLOBALS['user']->uid) {
    $variables['classes_array'][] = 'node-by-viewer';
  }

  $variables['title_attributes_array']['class'][] = 'node-title';

  // Returns the themed submitted-by string for the node.
  $variables['submitted'] = t('!datetime &nbsp;//&nbsp; by !username', array(
    '!username' => $variables['name'],
    '!datetime' => $variables['date'],
      ));

  $topic_links = array();
  // Returns the themed topics string for the node.
  if ($variables['node']->type == 'article') {
    $topics = field_get_items('node', $variables['node'], 'field_article_topics');
    if (!empty($topics)) {
      foreach ($topics as $topic) {
        $topic_name = field_view_value('node', $variables['node'], 'field_article_topics', $topic);
        $topic_links[] = l($topic_name['#markup'], 'blog/' . drupal_strtolower($topic_name['#markup']));
      }
    }
  }
  if ($variables['node']->type == 'portfolio') {
    $topics = field_get_items('node', $variables['node'], 'field_portfolio_category');
    if (!empty($topics)) {
      foreach ($topics as $topic) {
        $topic_name = field_view_value('node', $variables['node'], 'field_portfolio_category', $topic);
        $topic_links[] = l($topic_name['#markup'], 'portfolio/' . drupal_strtolower($topic_name['#markup']));
      }
    }
  }
  $variables['topic_links'] = ' &nbsp;//&nbsp; ' . implode(', ', $topic_links);

  // Returns the themed comment number string for the node.
  if ($variables['node']->comment_count == 0):
    $comment_text_link = t('No comments');
  else:
    $comment_text_link = format_plural($variables['node']->comment_count, t('@count comment'), t('@count comments'));
  endif;
  $variables['comment_number'] = ' &nbsp;//&nbsp; ' . l($comment_text_link, 'node/' . $variables['node']->nid, array('fragment' => 'comments'));
}

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 */
function boldy_preprocess_comment(&$variables) {
  // If comment subjects are disabled, don't display them.
  if (variable_get('comment_subject_field_' . $variables['node']->type, 1) == 0) {
    $variables['title'] = '';
  }

  // Anonymous class is broken in core. See #1110650
  if ($variables['comment']->uid == 0) {
    $variables['classes_array'][] = 'comment-by-anonymous';
  }
  // Zebra striping.
  if ($variables['id'] == 1) {
    $variables['classes_array'][] = 'first';
  }

  if ($variables['id'] == $variables['node']->comment_count) {
    $variables['classes_array'][] = 'last';
  }
  $variables['classes_array'][] = $variables['zebra'];

  $variables['title_attributes_array']['class'][] = 'comment-title';

  // Returns the themed submitted-by string for the comment.
  $variables['submitted'] = "<strong>" . $variables['author'] . "</strong> ";
  $variables['submitted'] .= "<span class='author'>" . t('!datetime', array('!datetime' => $variables['created'])) . "</span>";
}

/**
 * Preprocess variables for region.tpl.php
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
function boldy_preprocess_region(&$variables, $hook) {
  // Sidebar regions get some extra classes and a common template suggestion.
  if (strpos($variables['region'], 'sidebar_') === 0) {
    $variables['classes_array'][] = 'column';
    $variables['classes_array'][] = 'sidebar';
    $variables['theme_hook_suggestions'][] = 'region__sidebar';
    // Allow a region-specific template to override Boldy's region--sidebar.
    $variables['theme_hook_suggestions'][] = 'region__' . $variables['region'];
  }
}

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function boldy_preprocess_block(&$variables, $hook) {
  // Classes describing the position of the block within the region.
  if ($variables['block_id'] == 1) {
    $variables['classes_array'][] = 'first';
  }
  // The last_in_region property is set in boldy_page_alter().
  if (isset($variables['block']->last_in_region)) {
    $variables['classes_array'][] = 'last';
  }
  $variables['classes_array'][] = $variables['block_zebra'];

  $variables['title_attributes_array']['class'][] = 'block-title';
}

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function boldy_process_block(&$variables, $hook) {
  // Drupal 7 should use a $title variable instead of $block->subject.
  $variables['title'] = $variables['block']->subject;
}

/**
 * Implements hook_page_alter().
 *
 * Look for the last block in the region. This is impossible to determine from
 * within a preprocess_block function.
 *
 * @param $page
 *   Nested array of renderable elements that make up the page.
 */
function boldy_page_alter(&$page) {
  // Look in each visible region for blocks.
  foreach (system_region_list($GLOBALS['theme'], REGIONS_VISIBLE) as $region => $name) {
    if (!empty($page[$region])) {
      // Find the last block in the region.
      $blocks = array_reverse(element_children($page[$region]));
      while ($blocks && !isset($page[$region][$blocks[0]]['#block'])) {
        array_shift($blocks);
      }
      if ($blocks) {
        $page[$region][$blocks[0]]['#block']->last_in_region = TRUE;
      }
    }
  }
}

/**
 * Generates IE CSS links for LTR and RTL languages.
 */
function boldy_get_ie_styles() {
  global $language;

  $iecss = '<link type="text/css" rel="stylesheet" media="all" href="' . base_path() . path_to_theme() . '/css/fix-ie.css" />';
  if ($language->direction == LANGUAGE_RTL) {
    $iecss .= '<style type="text/css" media="all">@import "' . base_path() . path_to_theme() . '/css/fix-ie-rtl.css";</style>';
  }

  return $iecss;
}

/**
 * Implementation of hook form alter
 * Override the search box to add the graphic instead of the button.
 */
function boldy_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_block_form') {
    // @TODO consider adding this and replace js
//    $form['search_block_form']['#value'] = 'type your search...';
//    $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'type your search...';}";
//    $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'type your search...') {this.value = '';}";
//    $form['actions']['submit']['#value'] = '';
  }
  if ($form_id == 'contact_site_form') {
    $form['contact_information'] = array(
      '#markup' => "<div class='leftSide'>",
      '#weight' => 1,
    );
    $form['name']['#weight'] = 2;
    $form['mail']['#weight'] = 3;
    $form['subject']['#weight'] = 4;
    $form['contact_information_close'] = array(
      '#markup' => "</div>",
      '#weight' => 5,
    );
    $form['message_information'] = array(
      '#markup' => "<div class='rightSide'>",
      '#weight' => 6,
    );
    $form['message']['#weight'] = 7;
    $form['copy']['#weight'] = 8;
    $form['actions']['#weight'] = 9;
    $form['actions']['submit']['#value'] = t('Send');
    $form['message_information_close'] = array(
      '#markup' => "</div>",
      '#weight' => 10,
    );
  }
}

/**
 * Hide comment links on teaser views
 */
function boldy_links($links, $attributes = array('class' => 'links')) {
  if (count($links) > 0) {
    if (isset($links["node_read_more"])) {
      if (isset($links["comment_add"])) {
        unset($links["comment_add"]);
      }
      if (isset($links["comment_comments"])) {
        unset($links["comment_comments"]);
      }
    }
  }
  return theme_links($links, $attributes);
}

/**
 * Implements theme_field__field_type().
 */
function boldy_field__taxonomy_term_reference($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= $variables['label'];
  }

  // Render the items.
  $output .= ($variables['element']['#label_display'] == 'inline') ? '<ul class="links inline">' : '<ul class="links">';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<li' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
  }
  $output .= '</ul>';

  // Render the top-level DIV.
  $output = '<div class="postTags">' . $output . '</div>';

  return $output;
}

/**
 * Hide filter options on comments form
 * kudos to http://timonweb.com/how-remove-format-options-guideliness-comments-textarea-drupal-7
 */
function boldy_form_comment_form_alter(&$form, &$form_state, &$form_id) {
  $form['comment_body']['#after_build'][] = 'boldy_customise_comment_form';
}

function boldy_customise_comment_form(&$form) {
  // Note LANGUAGE_NONE, you may need to set your comment form language code instead
  $form[LANGUAGE_NONE][0]['format']['#access'] = FALSE;
  return $form;
}

function boldy_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.
  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }
    return '<div class="emm-paginate"><span class="emm-title">' . t('Pages') . ':</span> ' . theme('item_list', array(
          'items' => $items,
          'attributes' => array('class' => array('pager')),
        )) . '</div>';
  }
}
