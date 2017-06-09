<?php
namespace Drupal\slickplan\Form;

use Drupal;
use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\slickplan\Controller\SlickplanController;
use Drupal\slickplan\Controller\SlickplanToolController;

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

        $form['page_header'] = array(
            '#markup' => '<h2>Importing Pages&hellip;</h2>',
            '#attached' => [
                'library' => 'slickplan/awesome'
            ]
        );

        $form['slickplan_json'] = array(
            '#type' => 'hidden',
            '#value' => json_encode($xml['sitemap'])
        );

        $slickplan = new SlickplanController();

        $form['slickplan_html'] = array(
            '#type' => 'hidden',
            '#value' => $slickplan->getSummaryRow(array(
                'title' => '{title}',
                'loading' => 1
            ))
        );

        $form['page_header'] = array(
            '#markup' => '<h2>Importing Pages&hellip;</h2>'
        );

        $form['message'] = array(
            '#markup' => '<p style="display: none" class="slickplan-show-summary">Pages have been imported. Thank you for using ' . '<a href="http://slickplan.com/" target="_blank">Slickplan</a> Importer.</p>'
        );

        $form['progress'] = array(
            '#type' => 'inline_template',
            '#template' => '
                <div class="progress">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ complete }}"
                aria-valuemin="0" aria-valuemax="100" style="width:{{ complete }}%">
                {{ complete }}% Complete (success)
                </div>
                </div>',
            '#context' => array(
                'complete' => '0'
            ),
            '#attached' => array(
                'library' => 'slickplan/multifile'
            ),
        );

        // $form['progress'] = array(
        // '#markup' => theme_progress_bar(array(
        // 'percent' => 0,
        // 'message' => '',
        // )),
        // );

        $form['summary'] = array(
            '#markup' => '<p><hr /></p><div class="slickplan-summary"></div><p><hr /></p>',
            '#attached' => [
                'library' => 'slickplan/awesome'
            ]

        );

        $form['submit'] = array(
            '#type' => 'link',
            '#href' => 'admin/content',
            '#title' => 'See all pages',
            '#attributes' => array(
                'style' => 'display: none',
                'class' => 'slickplan-show-summary'
            )
        );

        return $form;
    }

    public function ajaxRequest()
    {
        set_time_limit(180);
        $result = array(
            'error' => 'Unknown Error',
        );

        $form = Drupal::request()->request->get('slickplan');

        if (is_array($form)) {
            $xml = Drupal::state()->get('slickplan_importer');

            $slickplan = new SlickplanController;
            $slickplan->options = $xml['import_options'];
            if (isset($xml['pages'][$form['page']]) and is_array($xml['pages'][$form['page']])) {
                $mlid = (isset($form['mlid']) and $form['mlid'])
                    ? $form['mlid']
                    : 0;
                $result = $slickplan->importPage($xml['pages'][$form['page']], $mlid);
                $result['html'] = $slickplan->getSummaryRow($result);
            }
            if (isset($form['last']) and $form['last']) {
                $result['last'] = $form['last'];
                $slickplan->checkForInternalLinks();
                Drupal::state()->set('slickplan_importer', $xml);
                Drupal::state()->resetCache();
            }
            else {
                $xml['import_options'] = $slickplan->options;
                Drupal::state()->set('slickplan_importer', $xml);
                Drupal::state()->resetCache();
            }
        }
        return new JsonResponse($result);
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
    }
}


