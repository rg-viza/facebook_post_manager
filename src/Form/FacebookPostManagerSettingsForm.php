<?php
namespace Drupal\facebook_post_manager\Form;
/**
 * Created by PhpStorm.
 * User: ndavis
 * Date: 11/14/17
 * Time: 9:32 PM
 */

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

class FacebookPostManagerSettingsForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'facebook_post_manager_admin_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'facebook_post_manager.settings',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
      $config = $this->config('facebook_post_manager.settings');
      $form['client_id'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Client ID'),
        '#default_value' => $config->get('client_id'),
      );
      $form['client_secret'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Client Secret'),
        '#default_value' => $config->get('client_secret'),
      );
      $form['default_graph_version'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Default Graph-SDK Version'),
        '#default_value' => $config->get('default_graph_version'),
      );
      $form['facebook_groups'] = array(
        '#type' => 'textarea',
        '#title' => $this->t('Facebook Groups'),
        '#default_value' => $config->get('facebook_groups'),
        '#cols' => 40,
      );
      return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $values = $form_state->getValues();
      $this->config('facebook_post_manager.settings')
        ->set('client_id', $form_state->getValue('client_id'))
        ->set('client_secret', $form_state->getValue('client_secret'))
        ->set('default_graph_version', $form_state->getValue('default_graph_version'))
        ->set('facebook_groups', $form_state->getValue('facebook_groups'))
        ->save();
    }


}