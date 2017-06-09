<?php
namespace Drupal\slickplan\Form;

use Drupal;
use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
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
            '#ajax' => array(
                'callback' => array(
                    $this,
                    'getSummaryPages'
                ),
                'effect' => 'fade',
                'event' => 'change',
                'progress' => array(
                    'type' => 'bar',
                    'message' => NULL
                )
            )
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

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        exit();
    }
}


