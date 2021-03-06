<?php

use Entities\AdminEntity;

/**
 * Renders a system default template, which is essentially a PHP template.
 *
 * @param string $template_file
 *   Template file path.
 * @param array $variables
 *    A keyed array of variables that will appear in the output.
 *
 * @return string
 *   The output generated by the template.
 */
function render_template($template_file, $variables)
{
  // Extract the variables to a local namespace
  extract($variables, EXTR_SKIP);

  // Start output buffering
  ob_start();

  // Include the template file
  include SITE_ROOT . '/' . $template_file;

  // End buffering and return its contents
  return ob_get_clean();
}

/**
 * Generate html output for error or status messages.
 *
 * @param array $messages
 *   Error and status messages.
 *   e.g. array(
 *          'errors' => array(
 *             'email' => array('Error message1', 'Error message 2'),
 *           ),
 *           'status' => array(
 *              'admin saved',
 *           )
 *         )
 *
 * @return string
 *   Messages html output.
 */
function generate_messages($messages)
{
  $output = '<ul>';
  foreach ($messages as $type => $typeMessages) {
    foreach ($typeMessages as $key => $messages) {
      if (is_array($messages)) {
        foreach ($messages as $message) {
          if (is_numeric($key)) {
            $output .= '<li class="' . $type . '">' . $message . '</li>';
          } else {
            $output .= '<li class="' . $type . '">' . $key . ' - ' . $message . '</li>';
          }

        }
      } else {
        $output .= '<li class="' . $type . '">' . $messages . '</li>';
      }
    }
  }
  $output .= '</ul>';
  return $output;
}

/**
 * Get current admin from session.
 *
 * @return NULL|AdminEntity
 *    Current admin Object from session
 */
function get_session_entity($entity)
{
  if ($entity == 'admin') {
    global $admin;
    session_start();
    if (isset($_SESSION['admin'])) {
      $admin = $_SESSION['admin'];
      return $admin;
    }
    return $admin;
  }
  elseif ($entity == 'user'){
    global $user;
    if (isset($_SESSION['user'])) {
      $user = $_SESSION['user'];
      return $user;
    }
    return $user;
  }
}

function redirect($path, $code = 302)
{
  header('Location: ' . $path, TRUE, $code);
	exit();
}

/**
 * Set a message is session.
 *
 * @param string $type
 *   Message type.
 * @param string $message
 *   Message text.
 */
function set_message($type, $message) {
  if (!isset($_SESSION)) {
    session_start();
  }
  $messages = !empty($_SESSION['messages']) ? $_SESSION['messages'] : array();
  $messages[$type][] = $message;
  $_SESSION['messages'] = $messages;
}

/**
 * Get messages from session.
 *
 * @return array
 *   List of messages, keyed by type.
 */
function get_messages() {
  if (!isset($_SESSION)) {
    session_start();
  }
  $messages = array();
  if (!empty($_SESSION['messages'])) {
    $messages = $_SESSION['messages'];
    unset($_SESSION['messages']);
  }
  return $messages;
}
