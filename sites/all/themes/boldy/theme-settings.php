<?php

/**
 * @file
 * Theme setting callbacks for the boldy theme.
 */
/**
 * Implements hook_form_system_theme_settings_alter().
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function boldy_form_system_theme_settings_alter(&$form, &$form_state) {

  $form['social_links'] = array(
    '#type' => 'fieldset',
    '#title' => t('Social Links'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['social_links']['boldy_twitter_user'] = array(
    '#type' => 'textfield',
    '#title' => t('Twitter Username'),
    '#default_value' => theme_get_setting('boldy_twitter_user'),
    '#description' => t("Your twitter username."),
  );
  $form['social_links']['boldy_latest_tweet'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display Latest Tweet'),
    '#default_value' => theme_get_setting('boldy_latest_tweet'),
    '#description' => t("If checked displays the latest status update using the username above."),
  );
  $form['social_links']['boldy_facebook_link'] = array(
    '#type' => 'textfield',
    '#title' => t('Facebook link'),
    '#default_value' => theme_get_setting('boldy_facebook_link'),
    '#description' => t("Enter the full URL of your Facebook profile."),
  );
  $form['social_links']['boldy_linkedin_link'] = array(
    '#type' => 'textfield',
    '#title' => t('LinkedIn link'),
    '#default_value' => theme_get_setting('boldy_linkedin_link'),
    '#description' => t("Enter the full URL of your Linkedin profile."),
  );
  $form['boldy_blurb_link'] = array(
    '#type' => 'textfield',
    '#title' => t('Request Quote Link'),
    '#default_value' => theme_get_setting('boldy_blurb_link'),
    '#description' => t("You can either enter a link manually or enter a page to point at."),
  );
  $form['footer'] = array(
    '#type' => 'fieldset',
    '#title' => t('Footer Twitter &amp; Quick Contact'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['footer']['boldy_footer_actions'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display Footer Twitter &amp; Quick Contact Section'),
    '#default_value' => theme_get_setting('boldy_footer_actions'),
    '#description' => t("If checked displays the Footer &amp; Quick Contact Section on each page."),
  );
  $form['footer']['boldy_actions_hide'] = array(
    '#type' => 'select',
    '#title' => t('Default Section Visibility'),
    '#options' => array('visible' => t('Visible'), 'hidden' => t('Hidden')),
    '#default_value' => theme_get_setting('boldy_actions_hide'),
    '#description' => t("Will the Footer Twitter &amp; Quick Contact Section be visible or hidden by default on page load."),
  );
  $form['boldy_contact_email'] = array(
    '#type' => 'textfield',
    '#title' => t('Email Address for Contact Form'),
    '#default_value' => theme_get_setting('boldy_contact_email'),
    '#description' => t("The email address you wish the contact form responses to go to."),
  );
}