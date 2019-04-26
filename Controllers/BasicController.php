<?php
/**
 * Created by PhpStorm.
 * User: Laurentiu
 * Date: 21/08/2018
 * Time: 10:10
 */

namespace Controllers;

class BasicController
{

  protected $title = '';
  protected $css = '';
  protected $content = '';
  protected $scriptElements = '';
  protected $sidebar = '';
  protected $menu = array(
    "/" => "Home",
  );
  protected  $loggedInAdmin = FALSE;
  protected  $loggedInUser = FALSE;

  public function __construct()
  {
    get_session_entity('admin');
    get_session_entity('user');
    global $admin,$user;
    if(isset($admin)){
      $this->loggedInAdmin = TRUE;
      $this->menu["/admin"] = "Admin";
    }
    if(isset($user)){
      $this->loggedInUser = TRUE;
      $this->menu["/user"] = "User";

    }
  }

  public function get()
  {
  }

  /**
   * Render template with custom variables.
   *
   * @param string $template
   *   Template path.
   * @param array $vars
   *   Variables to be used by template.
   *
   * @return string
   *   Html output.
   */
  function render($template, $vars = array())
  {
    return render_template($template, $vars);
  }

  /**
   * Export controller specific properties in array.
   *
   * This array will be passed to layout together with custom variables.
   * @see renderLayout().
   *
   * @return array
   *   Controller layout variables.
   */
  function getLayoutVars()
  {
    return array(
      'content' => $this->content,
      'title' => $this->title,
      'css' => $this->css,
      'scriptElements' => $this->scriptElements,
      'sidebar' => $this->sidebar,
      'menu' => $this->getMenuHtml(),
      'loggedInAdmin' => $this->loggedInAdmin,
      'loggedInUser' => $this->loggedInUser,
      'messages' => generate_messages(get_messages()),
    );
  }

  function getMenuHtml()
  {
    $output = '<ul class="nav navbar-nav">';
    foreach ($this->menu as $path => $menu) {
      $output .= '<li ' . ($path === $_GET['q'] ? 'class="active"' : '') . "><a href='$path'>$menu</a>";
    }
    $output .= '</ul>';
    return $output;
  }

  /**
   * Render output using a given template.
   *
   * @param string $layout
   *   Layout path.
   * @param array $customVars
   *   Variables to be used by layout.
   */
  function renderLayout($layout, $customVars = array())
  {
    $vars = $customVars + $this->getLayoutVars();
    echo $this->render($layout, $vars);
  }

  /**
   * Css output using a given css file
   *
   * @param string $href
   */
  public function addCSS($href)
  {
    $this->css .= "<link href='$href' rel='stylesheet' />";
  }

  public function addScript($src)
  {
    $this->scriptElements .= "<script src='$src'></script>";
  }

}