<?php
namespace Drupal\slickplan\Form;

use Drupal;
use Drupal\file\Entity\File;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\slickplan\Controller\SlickplanController;
use Symfony\Component\HttpFoundation\RedirectResponse;


class UploadForm extends FormBase
{

    public function getFormId()
    {
        return 'upload_file_forms';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $file_validator = array(
            'file_validate_extensions' => array(
                'xml'
            ),
            'file_validate_size' => array(
                file_upload_max_size()
            )
        );
        
        $form['slickplan'] = array(
            '#type' => 'managed_file',
            '#upload_location' => 'public://upload/slickplan',
            '#title' => 'Slickplan Importer',
            '#required' => TRUE,
            '#description' => '<p>The Slickplan Importer plugin allows you to quickly import your ' . '<a href="http://slickplan.com" target="_blank">Slickplan</a> projects into your Drupal site.</p>' . '<p>Upon import, your pages, navigation structure, and content will be instantly ready in your CMS.</p>' . '<p>Pick a XML file to upload and click Import.</p>',
            '#upload_validators' => $file_validator
        );
        
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Submit'
        );
        
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {}

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $xml = $form_state->getValue('slickplan');
        $file = file_load($xml[0]);
        $data = file_get_contents($file->getFileUri());
        
        if (isset($data) && ! empty($data)) {
            
            try {
                $slickplan = new SlickplanController();
                $data = $slickplan->parseSlickplanXml($data);
                Drupal::state()->set('slickplan_importer',$data);
                
                return new RedirectResponse(Drupal::url('slickplan.options'));
                
            } catch (Exception $ex) {
                drupal_set_message($e->getMessage(), 'error');
                return false;
            }
        }
    }
}


