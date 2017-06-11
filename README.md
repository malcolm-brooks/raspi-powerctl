# Raspberry Pi PC Power Control

## Overview
This project contains the complete website root contents needed to host the PC Power Control site, allowing you to remotely power-on, sleep and shutdown one or more desktop computers.

## Requirements
* A Raspberry Pi or other GPIO equipped device capable of running a linux apache server.
* A GPIO controlled switch connecting the power switch pins of your PC's motherboards (or multiple of these if you wish to control more than one machine). A wireing diagram for this can be found [Here](http://www.overclock.net/t/1429479/remote-power-switch-for-my-pc-using-a-raspberry-pi/20#post_21592386).

## Getting Started
Install various dependancies and enable some extra apache modules.
```bash
$ sudo apt-get update
$ sudo apt-get install apache2 apache2-utils git libapache2-mod-php5 php5 python python-pip
$ sudo a2enmod php5 ssl
$ sudo a2dissite 000-default.conf
```
Clone this project into the apache website root directory.
```bash
$ sudo mkdir -p /var/www/html
$ sudo chown -R pi:pi /var/www/html
$ rm -r /var/www/html/*
$ git clone https://github.com/malcolm-brooks/pi-pc-power-switch.git /var/www/html/
```
Update the sudoers file to grand limited sudo permissions to the apache user ("www-data").
```bash
$ sudo visudo
```
Apache will need to run "vcgencmd" and "gpio-ctl.py" as root. Add the following lines:
```bash
# www-data ALL=(root) NOPASSWD: /var/www/html/cgi-bin/gpio-ctl.cgi [0-9] [0-9], /var/www/html/cgi-bin/gpio-ctl.cgi [0-9][0-9] [0-9]
# www-data ALL=(root) NOPASSWD: /opt/vc/bin/vcgencmd
``` 
Install the apache site configuration and then open /etc/apache2/sites-available/001-powerctl.conf" with your favourite text editor to update "raspberrypi" to the FQDN of your Raspberry Pi and "foobarbaz" to your DDNS domain name.
```bash
$ sudo cp /var/www/html/apache2_config/001-powerctl.conf /etc/apache2/sites-available/
```
Enable the site and restart the apache service to ensure the changes have been picked up.
```bash
$ sudo a2ensite 001-powerctl
$ sudo service apache2 restart
```
You should now be able to access your website via the hostname/ip address of your raspberry pi. Note, at this point you have not made this available to the outside world. If you chose to do this, it is reconmended that you enable ssl enacryption with a self-signed certificate (instructions available [Here](https://www.digitalocean.com/community/tutorials/how-to-create-a-ssl-certificate-on-apache-for-ubuntu-14-04)), and use apache to create user logins (instructions available [Here](https://www.digitalocean.com/community/tutorials/how-to-set-up-password-authentication-with-apache-on-ubuntu-14-04)) to stop unintended users being able to remote switch off your devices! The provided apache config can be used with such a setup.

## Customization
Devices can be added to the site by creating 'includes/config.json' (relative to your website root directory). It is also possible customise the website's title or footer text from this file. See the example configuration below.
```bash
$ tree /var/www/html
/var/www/html
├── apache2_config
│   └── 001-powerctl.conf
├── includes
│   ├── config.json
│   ├── footer.php
│   └── header.php
├── index.php
├── login.php
├── private
│   ├── action
│   │   ├── power-switch.php
│   │   └── service.php
│   ├── cgi-bin
│   │   └── gpio-ctl.cgi
│   ├── devices.php
│   └── services.php
├── README.md
├── requirements.txt
└── static
    ├── favicon.ico
    └── styles.css
$
$ cat includes/config.json
{
  "site":
    {
      "title": "System Control",
      "footer": "Hosted by Anonymous Coward"
    },
  "devices":
  [
    {
      "name": "Anonymous Coward's Desktop",
      "owner": "anonymouscoward",
      "host": "anonymous-desktop.local",
      "gpio": 7
    }
  ],
  "services":
  [
    {
      "name": "My Service"
      "user": "serviceuser",
      "host": "anonymous-desktop.local",
      "command": "service_name",
      "options": [ "start", "stop", "status" ]
    }
  ]
}
```
