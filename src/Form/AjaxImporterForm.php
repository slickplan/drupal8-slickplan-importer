<?php

namespace Drupal\slickplan\Form;

use Drupal;
use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\slickplan\Controller\SlickplanController;

class AjaxImporterForm extends FormBase
{
    public function getFormId()
    {
        return 'ajax_importer_forms';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $xml = Drupal::state()->get('slickplan_importer');

        $slickplanToolController = new SlickplanToolController();
        $slickplanToolController->checkRequiredData($xml, 'ajax_importer');

        drupal_add_css('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(
            'type' => 'external',
        ));

        $slickplan = new Slickplan_Importer;

        $path = drupal_get_path('module', 'slickplan_importer');
        drupal_add_js(
            'window.SLICKPLAN_JSON = ' . json_encode($xml['sitemap']) . ';'
            ."\n"
            . 'window.SLICKPLAN_HTML = "' . str_replace('"', '\"', $slickplan->getSummaryRow(array(
                'title' => '{title}',
                'loading' => 1,
            )))
            . '";'
        ,'inline');
        drupal_add_js($path . '/js/ajax_importer.js');

        $form['page_header'] = array(
            '#markup' => '<h2>Importing Pages&hellip;</h2>',
        );

        $form['message'] = array(
            '#markup' => '<p style="display: none" class="slickplan-show-summary">Pages have been imported. Thank you for using '
                . '<a href="http://slickplan.com/" target="_blank">Slickplan</a> Importer.</p>',
        );

        $form['progress'] = array(
            '#markup' => theme_progress_bar(array(
                'percent' => 0,
                'message' => '',
            )),
        );

        $form['summary'] = array(
            '#markup' => '<p><hr /></p><div class="slickplan-summary"></div><p><hr /></p>',
        );

        $form['submit'] = array(
            '#type' => 'link',
            '#href' => 'admin/content',
            '#title' => 'See all pages',
            '#attributes' => array(
                'style' => 'display: none',
                'class' => 'slickplan-show-summary',
            ),
        );

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
    }
}


