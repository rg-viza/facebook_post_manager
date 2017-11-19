<?php
/**
 * Created by PhpStorm.
 * User: ndavis
 * Date: 11/18/17
 * Time: 9:46 PM
 */

namespace Drupal\facebook_post_manager\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\facebook_post_manager\Repository\FacebookRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FacebookPostManager extends ControllerBase {
  /**
   * {@inheritdoc}
   */
  public function post()
  {
    return print_r($_SERVER,true);
  }
  /**
   * {@inheritdoc}
   */
  public function callback()
  {
    $repo = new FacebookRepository();
    $node_id = \Drupal::service('user.private_tempstore')->get('facebook_post_manager')->get('current_node');
    if($repo->getToken())
    {
      $url = "/node/{$node_id}/edit";
    }
    else
    {
      $url = "/facebook/post/manager/initialize{$node_id}";
    }
    $response = new RedirectResponse($url);
    $response->send(); // don't send the response yourself inside controller and form.
  }
  /**
   * {@inheritdoc}
   */
  public function delete()
  {
    return print_r($_SERVER,true);
  }
  /**
   * {@inheritdoc}
   */
  public function initialize($node_id)
  {
    $repo =  new FacebookRepository();
    \Drupal::service('user.private_tempstore')->get('facebook_post_manager')->set('current_node', $node_id);
    $response = [
      '#type' => 'markup',
      '#markup' => $repo->sendToLogin(),
    ];
    return $response;
  }
}