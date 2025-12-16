/**
 * Frontend JavaScript.
 *
 * @package WPStarterPlugin
 */

(function () {
  'use strict';

  /**
   * Initialize frontend functionality.
   */
  function init() {
    // Add your frontend JavaScript here
    console.log('WP Starter Plugin frontend loaded');
  }

  // Initialize on DOMContentLoaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
