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
You should now be able to access your website via the hostname/ip address of your raspberry pi. Note, at this point you have not made this available to the outside world. If you chose to do this, it is reconmended that you enable ssl encryption with a free certificate "Let's Encrypt" (instructions available [Here](https://github.com/Neilpang/acme.sh)), and use apache to create user logins (instructions available [Here](https://www.digitalocean.com/community/tutorials/how-to-set-up-password-authentication-with-apache-on-ubuntu-14-04)) to stop unintended users being able to remote switch off your devices! The provided apache config can be used with such a setup.

## Customization
Devices and services can be added to the site by creating 'private/private.config' (relative to your website root directory).
A custom title and/or footer can be added to the site by creating 'site.config' (relative to your website root directory). 
See the example configuration below.
```bash
$ tree /var/www/html
/var/www/html
├── apache2_config
│   └── 001-powerctl.conf
├── includes
│   ├── footer.php
│   └── header.php
├── login.php
├── private
│   ├── action
│   │   ├── power-switch.php
│   │   └── service.php
│   ├── cgi-bin
│   │   └── gpio-ctl.cgi
│   ├── devices.php
│   ├── private.config
│   └── services.php
├── README.md
├── requirements.txt
├── site.config
└── static
    ├── favicon.ico
    └── styles.css
$
$ cat private/private.config
{
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
$
$ cat site.config
{
  "title": "System Control",
  "footer": "Hosted by Anonymous Coward"
}
```
### Services
When adding services, the apache user ("www-data") will have to make a passwordless ssh connection to the host on which the service is run in order to execute commands to affect that service. This presents a security issue as anyone using this site could then potentially gain ssh access to that host.

To ensure this access is restriced as much as possible:
1. Give the local root user (on the device running this site) passwordless ssh access to the server (instructions available [Here](http://www.philchen.com/2007/07/28/how-to-enable-passwordless-authentication-with-ssh)).
2. Update the sudoers file to grand limited sudo permissions to the apache user ("www-data").
```bash
$ sudo visudo
```
3. In the example private/private.config Apache will need to run "service_name start", "service_name stop" and "service_name status" as root. Add the following lines:
```bash
www-data ALL=(root) NOPASSWD: /usr/bin/ssh serviceuser@anonymous-desktop.local service_name start
www-data ALL=(root) NOPASSWD: /usr/bin/ssh serviceuser@anonymous-desktop.local service_name stop
www-data ALL=(root) NOPASSWD: /usr/bin/ssh serviceuser@anonymous-desktop.local service_name status
```
