<?php
/**
 * Created by PhpStorm.
 * User: ndavis
 * Date: 11/18/17
 * Time: 7:36 PM
 */

namespace Drupal\facebook_post_manager\Repository;


interface FacebookRepositoryInterface {
  public function getToken();
  public function sendToLogin();
}