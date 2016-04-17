# Presky

Collect and visualize pharmaceutical data! Be able to learn the trends of drugs that are being prescribed to be able to forecast supply and demand to prevent wastage.

## Some Screenshots:
![](https://dl2.pushbulletusercontent.com/ZrdiqKafeEPlVARSQRMAbGfQ4qnzBQ1X/Screen%20Shot%202016-04-17%20at%2010.36.27%20AM.png)
![](https://dl2.pushbulletusercontent.com/5ia2Ck6hfYpxL4mVBegO9MeHv2Xm7yWl/Screen%20Shot%202016-04-17%20at%2010.36.59%20AM.png)
![](https://dl2.pushbulletusercontent.com/PP1JvmTgVG58rbinDr1RItzYPBeMnGSh/Screen%20Shot%202016-04-17%20at%2010.39.08%20AM.png)

## Installation of Presky into your own PHP servers

In order for you to install Presky, your server would need to have the following requirements:

* PHP Version 5.6 or Higher
* MySQL Server Version 5 or Higher

In addition to that, you would need to set up your MySQL Database through this file saved at `configuration/db.php` by editing with your own database information.

## Additional notes regarding the installation

Bundled with this installation of Presky contains the [Mailgun Rest API](https://mailgun.com). This API is for use of the Secure Sign On Login feature within Presky to enable a secure cloud between the end user and the server. It is highly recommended for you to supply your own API key and information located at `functions/curlToServer.php`.
