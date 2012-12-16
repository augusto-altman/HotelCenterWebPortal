<?php

/**
 * @file
 * Boldy's theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template normally located in the
 * modules/system folder.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/boldy.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['header']: Items for the header region.
 * - $page['nav_bar']: Items for the navigation bar region.
 * - $page['search_box']: Items for the search box region.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['slider']: Items for the slider content region.
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['content_top']: The Items for the top content region.
 * - $page['content']: The main content of the current page.
 * - $page['content_bottom']: The Items for the bottom content region.
 * - $page['right']: Items for the right sidebar.
 * - $page['footer_actions']: Items for the footer action region.
 * - $page['footer_message']: Items for the footer message region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see boldy_process_page()
 */
?>
              <?php if ($site_title): ?>
                <div class="picurito-rojo"><?php print $site_html; ?></div>
              <?php endif; ?>
  <?php print render($page['header']); ?>
    <div id="mainWrapper"> <!-- BEGIN MAINWRAPPER -->
      <div id="wrapper" class="clearfix"> <!-- BEGIN WRAPPER -->
        <div id="header"> <!-- BEGIN HEADER -->
          <?php if ($logo || $site_title): ?>
            <div id="logo"><p class="page-title">
              <?php if ($logo): ?>
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"><img src="<?php print $logo; ?>" alt="<?php print $site_name_and_slogan; ?>" /></a>
              <?php endif; ?>
            </p></div>
          <?php endif; ?>
          <div id="mainMenu"> <!-- BEGIN MAIN MENU -->
            <?php if ($primary_nav): print $primary_nav; endif; ?>
            <?php if ($secondary_nav): print $secondary_nav; endif; ?>
            <?php if ($page['nav_bar']): ?>
              <?php print render($page['nav_bar']); ?>
            <?php endif; ?>
          </div> <!-- END MAIN MENU -->
          <?php if ($page['search_box']): ?>
            <div id="topSearch"> <!-- BEGIN TOP SEARCH -->
              <?php print render($page['search_box']); ?>
            </div> <!-- END TOP SEARCH -->
          <?php endif; ?>
          
        </div> <!-- END HEADER -->
        <div id="main-content"> <!-- BEGIN CONTENT -->
         
          <div>
            <?php if ($tabs): ?><div id="tabs-wrapper" class="clearfix"><?php endif; ?>
            <?php print render($title_prefix); ?>
            <?php if ($title): ?>
              <h1<?php print $tabs ? ' class="with-tabs title"' : ' class="title"' ?>><?php print $title ?></h1>
            <?php endif; ?>
            <?php print render($title_suffix); ?>
            <?php if ($tabs): ?><?php print render($tabs); ?></div><?php endif; ?>
            <?php print render($tabs2); ?>
            <?php print $messages; ?>
            <?php print render($page['help']); ?>
            <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
            <?php if ($page['content_top']): ?>
              <div id="content-top" class="region region-content_top">
                <?php print render($page['content_top']); ?>
              </div> <!-- /#content-top -->
            <?php endif; ?>
            <div id="content-area" class="clear-block">
     <div class="bi_contacto">
              <?php print render($page['content']); ?>
     </div>
            </div>
            <?php if ($feed_icons): ?>
              <div class="feed-icons"><?php print $feed_icons; ?></div>
            <?php endif; ?>
            <?php if ($page['content_bottom']): ?>
              <div id="content-bottom" class="region region-content_bottom">
                <?php print render($page['content_bottom']); ?>
              </div> <!-- /#content-bottom -->
            <?php endif; ?>
          </div>
        </div> <!-- END CONTENT -->
      </div> <!-- END WRAPPER -->
      <div id="footer"> <!-- BEGIN FOOTER -->
        <?php if (theme_get_setting('boldy_footer_actions')): ?>
          <div style="width:960px; margin: 0 auto; position:relative;">
            <a href="#" id="showHide" <?php if (theme_get_setting('boldy_actions_hide') == "hidden"): print 'style="background-position:0 -16px"';
            endif; ?>>Show/Hide Footer Actions</a>
          </div>
          <div id="footerActions" <?php if (theme_get_setting('boldy_actions_hide') == "hidden"): print 'style="display:none"';
            endif; ?>>
            <div id="footerActionsInner">
              <?php if (theme_get_setting('boldy_twitter_user') != "" && theme_get_setting('boldy_latest_tweet')): ?>
                <div id="twitter">
                  <a href="http://twitter.com/<?php print theme_get_setting('boldy_twitter_user'); ?>" class="action">Follow Us!</a>
                  <div id="latest">
                    <div id="tweet">
                      <div id="twitter_update_list"></div>
                    </div>
                    <div id="tweetBottom"></div>
                  </div>
                </div>
              <?php endif; ?>
              <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
              <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php print theme_get_setting('boldy_twitter_user'); ?>.json?callback=twitterCallback2&amp;count=<?php
            if (theme_get_setting('boldy_number_tweets') != "") {
              print theme_get_setting('boldy_number_tweets');
            }
            else {
              print "1";
            }
            ?>">
              </script>
              <?php if ($page['footer_actions']): ?>
                <div id="quickContact">
                  <?php print render($page['footer_actions']); ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
        <div id="footerWidgets">
          <div id="footerWidgetsInner">
            <?php if ($page['footer']): ?>
              <?php print render($page['footer']); ?>
            <?php endif; ?>
            <?php if ($page['footer_message']): ?>
              <div id="copyright"> <!-- BEGIN COPYRIGHT -->
                <?php print render($page['footer_message']); ?>
              </div> <!-- END COPYRIGHT -->
            <?php endif; ?>
          </div>
        </div>
      </div> <!-- END FOOTER -->
    </div> <!-- END MAINWRAPPER -->
