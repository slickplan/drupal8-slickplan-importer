<?php
namespace Drupal\slickplan\Controller;

use Drupal;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SlickplanToolController
{

    /**
     * Check if required data exists, if not redirect to upload form.
     *
     * @param
     *            $xml
     * @param string $page            
     */
    public function checkRequiredData($xml, $page = '')
    {
        if (($page === 'options' and ! isset($xml['sitemap'])) or ($page === 'summary' and ! isset($xml['summary'])) or ($page === 'ajax_importer' and (! isset($xml['sitemap']) or isset($xml['summary'])))) {
            return new RedirectResponse(Drupal::url('slickplan.upload'));
        }
    }
}