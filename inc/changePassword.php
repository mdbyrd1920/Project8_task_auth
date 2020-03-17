<?php
require_once __DIR__ . '/bootstrap.php';
requireAuth();

$currentPassword = request()->get('current_password');
$newPassword = request()->get('password');
$confirmPassword = request()->get('confirm_password');

//verifies new password
if ($newPassword != $confirmPassword) {

  $session->getFlashBag()->add('error', 'New passwords do NOT match. Please try again.');
  redirect('/account.php');
}

$user = getAuthenticatedUser();

if(empty($user)) {
  $session->getFlashBag()->add('error', "Some Error Happened. Try again");
  redirect('/account.php');
}

if(!password_verify($currentPassword, $user ['password'])) {
  $session->getFlashBag()->add('error', 'Current password was incorrect, please try again');
    redirect('/account.php');

}

//hash new password
$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

if (!updatePassword($hashed, $user['id'])) {
$session->getFlashBag()->add('error', 'Could not update password, please try again.');
    redirect('/account.php');
}

$session->getFlashBag()->add('success', 'Password Updated');
    redirect('/account.php');
