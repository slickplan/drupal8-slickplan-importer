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
        
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        
    }
}


