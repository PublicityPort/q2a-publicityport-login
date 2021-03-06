# Question2Answer PublicityPort Login #

## About ##

This is a plugin for **Question2Answer** that allows users to log in via Facebook, Google, Yahoo, Github and other OAuth/OpenID providers. 


## Description ##
This is an extension of the Facebook Login plugin, to which it adds a broad range of additional login providers. It is based on [HybridAuth](http://hybridauth.sourceforge.net/) library which acts as a middleware between the plugin and a wide range of OAuth and OpenID service providers. For this reason, it is possible to add any identity provider supported by HybridAuth to your Q2A installation with virtually no effort.

The plugin also offers the ability to link multiple OpenID/OAuth-powered logins to a Q2A user account, allowing users to log in to the same account via multiple providers. For example, an user might link his or her Facebook and Google accounts to the Q2A user account and then log in to the Q2A site through any of the 3 methods (Q2A login page, Facebook or Google).


## Installation ##

* Install [Question2Answer][]. This plugin requires at least version 1.6 (see the change log for details)
* Make sure the [cURL][] and [JSON][] extensions are installed and enabled in PHP. HybridAuth library requires these extensions in order to work properly.
* Get the source code for this plugin from [Github][], either using [Git][], or downloading directly:

   - To download using git, install git and then type 
      
      `git clone https://github.com/PublicityPort/q2a-publicityport-login.git`
      
   - To download directly, go to the [project page][Github] and click **[Download ZIP][download]**

* Copy the plugin folder to `qa-plugin` directory. It is recommended to remove the Facebook Login plugin that ships with Q2A.
* Rename the file `providers-sample.php` to `providers.php` and make it write-accessible to the user under which the web-server is running. The plugin code must be able to write to this file.


## Configuration ##

* Go to **Admin -> Plugins** on your Q2A installation and enable the providers which you would like to use. For all OAuth-based providers (all, except Yahoo, which uses OpenID) you need to provide some keys after you register your application with them. Check the documentation URL below each login provider in the administration page; it will take you to the HybridAuth documentation page which contains information on how to register your application and what Callback URL to use.
* Optionally, add the contents of the *qa-publicityport-login.css* file to your theme's CSS file and select the option **Don't inline CSS** from the **PublicityPort Login Configuration** section on the **Admin -> Plugins** page. 


### Technical notes ###

1. this plugin requires some database changes: a column called `oemail` (original email) will be added to `qa_users` table, and two columns called `oemail` and `ohandle` (original handle) will be added to `qa_user_logins` table. These columns will store the email and name associated with the OpenID/OAuth accounts when the users log in through any external provider. These emails from `oemail` fields will then be used to determine if there are accounts which can be linked together. The database changes will be performed when the administration page is accessed for the first time after the plugin is installed or upgraded. This is a one-time-only operation and it should not affect your existing data in any way.
2. Every time you save the configuration of the plugin in the administration page, the file `providers.php` is rewritten with the list of providers that are enabled. This list is then used during plugin initialization, when access to the database is restricted by the Q2A code (see `qa-plugin.php` for details). If the plugin cannot update this file, the list of active providers will be considered empty, no matter what is configured in the administration page, so users will not be able to log in using any service. This is why it is important for the plugin code to have write access to this file.

  [Question2Answer]: http://www.question2answer.org/install.php
  [Git]: http://git-scm.com/
  [Github]: https://github.com/PublicityPort/q2a-publicityport-login
  [cURL]: http://www.php.net/manual/en/book.curl.php
  [JSON]: http://www.php.net/manual/en/book.json.php
  [download]: https://github.com/PublicityPort/q2a-publicityport-login/archive/master.zip


### Adding new login providers ###

Since this plugin is based on [HybridAuth](http://hybridauth.sourceforge.net/), you can easily add new login providers to your Q2A site. All you need to do is to add the provider PHP file to the `Hybrid/Providers` folder and configure it from the Administration page. That's it! New providers can be downloaded from HybridAuth website.



### Handling login errors ###

Whenever a login attempt fails, the user will be redirected to the original page but no error message will be displayed. This is to save end-users from technical error messages which would not help them much anyway. If you instead would like to show an error message, you can do that through a layer or a custom theme. 

If something happens with the login process and authentication cannot be done, the user will be redirected to a page whose URL follows the following pattern: `yoursite.com/?provider=X&code=0`. The custom layer or theme could check if the two parameters are present in the URL and display an error message based on the code number. The descriptions of the error codes are below.

    0 : Unspecified error. Most likely Hybridauth is not correctly installed (perhaps cURL extension not enabled).
    1 : Hybriauth configuration error.
    2 : Provider not properly configured.
    3 : Unknown or disabled provider.
    4 : Missing provider application credentials.
    5 : Authentification failed. The user has canceled the authentication or the provider refused the connection.
    6 : User profile request failed. Most likely the user is not connected to the provider and he should authenticate again.
    7 : User not connected to the provider.
    8 : Provider does not support this feature.
    98 : The merge has already been performed and there are no more accounts to connect.
    99 : The external login could not be linked with current account because it is already connected with another administrator account.



## Translation ##

The translation file is **qa-publicityport-lang-default.php**.  Copy this file to the same directory and change the **"default"** part of the filename to your language code. Edit the right-hand side strings in this file, for example, changing: `'my_logins_title'=>'My logins',` to `'my_logins_title'=>'Mes comptes',`

Don't edit the string on the left-hand side. Once you've completed the translation, don't forget to set the site language in the admin control panel. Translations for Romanian are also included.

## Disclaimer ##
This code has not been extensively tested on high-traffic installations of Q2A. You should perform your own tests before using this plugin on a live (production) environment. 


## License ##
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.


## About Q2A ##
Question2Answer is a free and open source platform for Q&A sites. For more information, visit [http://www.question2answer.org/](http://www.question2answer.org/)
