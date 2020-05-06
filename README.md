# Mautic reCAPTCHA Plugin

[![license](https://img.shields.io/packagist/v/koco/mautic-recaptcha-bundle.svg)](https://packagist.org/packages/koco/mautic-recaptcha-bundle) 
[![Packagist](https://img.shields.io/packagist/l/koco/mautic-recaptcha-bundle.svg)](LICENSE)
[![mautic](https://img.shields.io/badge/mautic-3-blue.svg)](https://www.mautic.org/mixin/recaptcha/)

This Plugin brings reCAPTCHA integration to mautic 3.

Ideas and suggestions are welcome, feel free to create an issue or PR on Github.

Licensed under GNU General Public License v3.0.

## Installation via composer (preferred)
### mautic 2
Execute `composer require koco/mautic-recaptcha-bundle:1.*` in the main directory of the mautic installation.
### mautic 3
Execute `composer require koco/mautic-recaptcha-bundle:3.*` in the main directory of the mautic installation.

## Installation via .zip
Download the .zip file, extract it into the `plugins/` directory and rename the new directory to `MauticRecaptchaBundle`.

* Download for mautic 2: [mautic2.zip](https://github.com/KonstantinCodes/mautic-recaptcha/archive/1.1.3.zip)
* Download for mautic 3: [mautic3.zip](https://github.com/KonstantinCodes/mautic-recaptcha/archive/master.zip)

Clear the cache via console command `php app/console cache:clear --env=prod` (might take a while) *OR* manually delete the `app/cache/prod` directory.

## Configuration
Navigate to the Plugins page and click "Install/Upgrade Plugins". You should now see a "reCAPTCHA" plugin. Open it to configure site key and site secret.

![plugin config](/doc/config.png?raw=true "plugin config")

## Usage in Mautic Form
Add "reCAPTCHA" field to the Form and save changes.
![mautic form](/doc/form_preview.png?raw=true "Mautic Form with reCAPTCHA")
