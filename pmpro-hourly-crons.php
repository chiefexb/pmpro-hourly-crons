<?php
/*
Plugin Name: PMPro Hourly Crons
Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-hourly-crons/
Description: Run PMPro crons every hour instead of every day.
Version: 1.
Author: Stranger Studios
Author URI: http://www.strangerstudios.com
*/

/*
	Run PMPro crons every hour instead of every day.
	
	The PMPRO_CRON_LIMIT requires PMPro version 1.7.14.3 or higher.
	
	This plugin must be activated after PMPro is activated.
*/
//define('PMPRO_CRON_LIMIT', 30);	//optionally uncomment this and edit to slow down how many records are processed each hour
function pmprohc_activation()
{
	//clear hooks
	wp_clear_scheduled_hook('pmpro_cron_expiration_warnings');	
	wp_clear_scheduled_hook('pmpro_cron_expire_memberships');
	wp_clear_scheduled_hook('pmpro_cron_credit_card_expiring_warnings');   
	
	//schedule our new ones
	wp_schedule_event(current_time('timestamp'), 'hourly', 'pmpro_cron_expiration_warnings');
	wp_schedule_event(current_time('timestamp'), 'hourly', 'pmpro_cron_expire_memberships');
	wp_schedule_event(current_time('timestamp'), 'hourly', 'pmpro_cron_credit_card_expiring_warnings');
}
register_activation_hook(__FILE__, 'pmprohc_activation');

function pmprohc_deactivation()
{
	//clear hooks
	wp_clear_scheduled_hook('pmpro_cron_expiration_warnings');	
	wp_clear_scheduled_hook('pmpro_cron_expire_memberships');
	wp_clear_scheduled_hook('pmpro_cron_credit_card_expiring_warnings');   
	
	//reset if PMPro is still active
	if(function_exists('pmpro_hasMembershipLevel'))
	{
		wp_schedule_event(current_time('timestamp'), 'daily', 'pmpro_cron_expiration_warnings');
		wp_schedule_event(current_time('timestamp'), 'daily', 'pmpro_cron_expire_memberships');
		wp_schedule_event(current_time('timestamp'), 'monthly', 'pmpro_cron_credit_card_expiring_warnings');
	}
}
register_deactivation_hook(__FILE__, 'pmprohc_deactivation');