<?php

class SlickplanToolController
{

    /**
     * Check if required data exists, if not redirect to upload form.
     *
     * @param
     *            $xml
     * @param string $page            
     */
    public function _slickplan_check_required_data($xml, $page = '')
    {
        if (($page === 'options' and ! isset($xml['sitemap'])) or ($page === 'summary' and ! isset($xml['summary'])) or ($page === 'ajax_importer' and (! isset($xml['sitemap']) or isset($xml['summary'])))) {
            drupal_goto('admin/config/content/slickplan_importer');
        }
    }
}