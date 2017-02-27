# Raspberry Pi GPIO PC Power Switch

## Overview
This project contains the complete website root contents needed to host the Raspberry PI GPIO PC Power Switch site, allowing you to remotely power-on, sleep and shutdown one or more tower PCs.

## Requirements
* A Raspberry Pi or other GPIO equipped device capable of running a linux apache server.
* A GPIO controlled switch connecting the power switch pins of your PC's motherboards (or multiple of these if you wish to control more than one machine). A wireing diagram for this can be found [Here](http://www.overclock.net/t/1429479/remote-power-switch-for-my-pc-using-a-raspberry-pi/20#post_21592386).

## Getting Started
Install various dependancies and enable some extra apache modules.
```bash
$ sudo apt-get update
$ sudo apt-get install git python apache2-utils
$ sudo a2enmod mod_include ssl
$ sudo a2ensite default-ssl.conf
```
Clone this project into the apache website root directory.
```bash
$ sudo mkdir -p /var/www/html
$ sudo chown -R pi:pi /var/www/html
$ rm -r /var/www/html/*
$ git clone https://github.com/malcolm-brooks/pi-pc-power-switch.git /var/www/html/
```
Clone the [power-ctl.py](https://gist.github.com/malcolm-brooks/b33b279c036935638edb1014d5a6189b) into '/usr/scripts'. This python script will allow the site to communicate with the GPIO ports used to control your PC's power state.
```bash
$ sudo mkdir -p /usr/scripts
$ sudo git clone https://gist.github.com/b33b279c036935638edb1014d5a6189b.git /usr/scripts/
```
Restart the apache service to ensure the changes to modules etc have been picked up.
```bash
$ sudo service apache2 restart
```
You should now be able to access your website via the hostname/ip address of your raspberry pi. Note, at this point you have not made this available to the outside world. If you chose to do this, it is reconmended that you enable ssl enacryption with a self-signed certificate, and use apache to create user logins to stop unintended users being able to remote switch off your devices!

## Customization
Devices can be added to the site by creating 'includes/config.json' (relative to your website root directory). It is also possible customise the website's title or footer text from this file. See the example configuration below.
```bash
$ tree /var/www/html
/var/www/html
├── action
│   └── power-switch.php
├── includes
│   ├── config.json
│   ├── footer.php
│   └── header.php
├── index.php
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
      "host": "anonymous-desktop.local",
      "gpio": 7
    }
  ]
}
```
