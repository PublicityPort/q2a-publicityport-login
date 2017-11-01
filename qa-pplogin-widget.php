<?php

	class qa_publicityport_logins_widget {
		
		function allow_template($template) {
			
			// not allowed when logged in
			$userid = qa_get_logged_in_userid();
			if (stristr(qa_request(), 'admin/layoutwidgets') === false && isset($userid)) {
				return false;
			}

			if($template == 'login' || $template == 'register') {
				return false;
			}
			
			return true;
		}

		
		function allow_region($region) {
			return ($region == 'side');
		}
		

		function output_widget($region, $place, $themeobject, $template, $request, $qa_content) {
			$loginmodules=qa_load_modules_with('login', 'login_html');
			
			if(empty($loginmodules)) {
				return;
			}
			
			$themeobject->output(
				'<div class="publicityport-login-sidebar">',
				qa_lang_html('plugin_open/login_title'),
				'</div>',
				'<p class="publicityport-login-sidebar-buttons">'
			);
			
			foreach ($loginmodules as $tryname => $module) {
				ob_start();
				$module->login_html(isset($topath) ? (qa_opt('site_url').$topath) : qa_path($request, $_GET, qa_opt('site_url')), 'sidebar');
				$label=ob_get_clean();

				if (strlen($label))
					$themeobject->output($label);
			}
			
			$themeobject->output('</p>');

		}
		
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/