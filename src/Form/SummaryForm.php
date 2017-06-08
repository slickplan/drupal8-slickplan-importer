<?php
namespace Drupal\slickplan\Form;

use Drupal;
use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\slickplan\Controller\SlickplanController;
use Drupal\slickplan\Controller\SlickplanToolController;

class SummaryForm extends FormBase
{

    public function getFormId()
    {
        return 'summary_forms';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $xml = Drupal::state()->get('slickplan_importer');
        
        $slickplanToolController = new SlickplanToolController();
        $slickplanToolController->checkRequiredData($xml, 'summary');
        
        $form['page_header'] = array(
            '#markup' => '<h2>Success!</h2>'
        );
        
        $form['message'] = array(
            '#markup' => '<p>Pages have been imported. Thank you for using ' . '<a href="http://slickplan.com/" target="_blank">Slickplan</a> Importer.</p>'
        );
        
        if (isset($xml['summary']) and is_array($xml['summary'])) {
            $html = '';
            $slickplan = new Slickplan_Importer();
            foreach ($xml['summary'] as $page) {
                $html .= $slickplan->getSummaryRow($page);
            }
            if ($html) {
                $form['summary'] = array(
                    '#markup' => '<p><hr /></p>' . $html . '<p><hr /></p>'
                );
            }
        }
        
        $form['submit'] = array(
            '#type' => 'link',
            '#href' => 'admin/content',
            '#title' => 'See all pages'
        );
        
        Drupal::state()->set('slickplan_importer', array());
        
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {}

    public function submitForm(array &$form, FormStateInterface $form_state)
    {}
}


