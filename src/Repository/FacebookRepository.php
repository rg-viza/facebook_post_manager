<?php
/**
 * Created by PhpStorm.
 * User: ndavis
 * Date: 11/18/17
 * Time: 7:36 PM
 */


namespace Drupal\facebook_post_manager\Repository;
use Facebook\Facebook;
use  \Drupal\node\Entity\Node;

class FacebookRepository implements FacebookRepositoryInterface
{
  protected $fb;
  protected $lltoken;
  /**
   * FacebookRepository constructor.
   */
  public function __construct() {
    $config = \Drupal::config('facebook_post_manager.settings');
    $client_id=$config->get('client_id');
    $client_secret=$config->get('client_secret');
    $default_graph_version=$config->get('default_graph_version');
    $this->fb = new Facebook([
      'app_id' => "{$client_id}",
      'app_secret' => "{$client_secret}",
      'default_graph_version' => "{$default_graph_version}",
    ]);
  }

  public function getToken()
  {
    $helper = $this->fb->getRedirectLoginHelper();
    $accessToken = $helper->getAccessToken("http://{$_SERVER['HTTP_HOST']}/facebook/post/manager/callback");
    $client = $this->fb->getOAuth2Client();
    $llAccessToken = $client->getLongLivedAccessToken($accessToken);
    //$this->fb->setDefaultAccessToken($llAccessToken);
    \Drupal::service('user.private_tempstore')->get('facebook_post_manager')->set('lltoken', $llAccessToken);
    return true;
  }

  public function setToken($lltoken)
  {
    $this->fb->setDefaultAccessToken($lltoken);
  }

  public function sendToLogin()
  {
    $helper = $this->fb->getRedirectLoginHelper();
    $permissions = ['manage_pages','publish_pages']; // optional
    $callback = "http://{$_SERVER['HTTP_HOST']}/facebook/post/manager/callback";
    $loginUrl    = $helper->getLoginUrl($callback, $permissions);
    return '<a href="' . $loginUrl . '">Log in with Facebook</a>';
  }

  public function post($node, $group_id)
  {

    $group = trim($group_id);

    $body = $node->get('body')->getValue()[0]['value'];

    return $this->fb->post("/$group/feed",[
      'message'=>str_replace('&nbsp;',"\n", strip_tags($body)),
      'title'=>$node->getTitle(),
    ]);
  }

  public function delete($post_id)
  {
    return $this->fb->delete("/{$post_id}",[]);
  }
}