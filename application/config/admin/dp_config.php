<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['title']      = 'AdminFeedbacker';
$config['title_mini'] = 'FB';
$config['title_lg']   = 'Feedbacker';

/* Display panel login */
$config['auth_social_network'] = FALSE;
$config['forgot_password']     = FALSE;
$config['new_membership']      = FALSE;

$config['assets_dir']     = 'assets';
$config['frameworks_dir'] = $config['assets_dir'] . '/frameworks';
$config['plugins_dir']    = $config['assets_dir'] . '/plugins';

$config['upload_dir']     = 'uploads';
$config['avatar_dir']     = $config['upload_dir'] . '/avatar';

/*
 * **********************
 * AdminLTE
 * **********************
 */
/* Page Title */
$config['pagetitle_open']     = '<h1>';
$config['pagetitle_close']    = '</h1>';
$config['pagetitle_el_open']  = '<small>';
$config['pagetitle_el_close'] = '</small>';

/* Breadcrumbs */
$config['breadcrumb_open']     = '<ol class="breadcrumb">';
$config['breadcrumb_close']    = '</ol>';
$config['breadcrumb_el_open']  = '<li>';
$config['breadcrumb_el_close'] = '</li>';
$config['breadcrumb_el_first'] = '<i class="fa fa-dashboard"></i>';
$config['breadcrumb_el_last']  = '<li class="active">';
