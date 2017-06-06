<?php
namespace Drupal\slickplan\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class UploadForm extends FormBase
{

    public function getFormId()
    {
        return 'upload_file_forms';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $file_validator = array(
            'file_validate_extensions'=>array('xml'),
            'file_validate_size'=>array(file_upload_max_size())
        );
        
        $form['slickplan'] = array(
            '#type' => 'file',
            '#title' => 'Slickplan Importer',
            '#required' => TRUE,
            '#description' => '<p>The Slickplan Importer plugin allows you to quickly import your ' . '<a href="http://slickplan.com" target="_blank">Slickplan</a> projects into your Drupal site.</p>' . '<p>Upon import, your pages, navigation structure, and content will be instantly ready in your CMS.</p>' . '<p>Pick a XML file to upload and click Import.</p>',
            '#upload_validators'=>$file_validator,
        );
        
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Submit'
        );
        
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if (! $this->file) {
            $form_state->setErrorByName('slickplan', 'File not found');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $file = $form_state->getValue('file');
        
        if(isset($file) && is_file($file)) {
            drupal_set_message(file_get_contents($file));
        } else {
            drupal_set_message("Problem z plikiem!");
        }
    }
}


