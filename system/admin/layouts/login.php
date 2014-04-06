<?php
if ($user->isLoggedin()) {
    $session->redirect($config->urls->root . $config->adminUrl);
}

if (count($input->post)) {
    $session->login($input->post->username, $input->post->password);
    $session->redirect($config->urls->root . $config->adminUrl . "/login");
}


$output = "<label>Username</label>";
$output .= "<input class='field-input' name='username' type='text'>";
$output .= "<label>Password</label>";
$output .= "<input class='field-input' name='password' type='password'>";
$output .= "<button type='submit' class='button button-success'>Login</button>";
$output = "<form class='login-form' method='POST'>{$output}</form>";

