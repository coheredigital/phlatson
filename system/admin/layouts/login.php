<?php 

if (count($input->post)) {
	$session->login($input->post->username, $input->post->password);
	$session->redirect($input->query);
}

$output = "<label>Username</label>";
$output .= "<input class='field-input' name='username' type='text' value='adam'>";
$output .= "<label>Password</label>";
$output .= "<input class='field-input' name='password' type='text' value='N0n3848y'><br><br>";
$output .= "<button type='submit' class='button button-success'><i class='icon icon-lock'></i> Submit</button>";
$output = "<form method='POST'>{$output}</form>";