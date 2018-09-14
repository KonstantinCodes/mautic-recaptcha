# Mautic reCAPTCHA Plugin

[![license](https://img.shields.io/circleci/project/github/KonstantinCodes/mautic-recaptcha.svg)](https://circleci.com/gh/KonstantinCodes/mautic-recaptcha/tree/master) [![license](https://img.shields.io/packagist/v/koco/mautic-recaptcha-bundle.svg)](https://packagist.org/packages/koco/mautic-recaptcha-bundle) 
[![Packagist](https://img.shields.io/packagist/l/koco/mautic-recaptcha-bundle.svg)](LICENSE) [![mautic](https://img.shields.io/badge/mautic-%3E%3D%202.11-blue.svg)](https://www.mautic.org/mixin/recaptcha/)

This Plugin brings reCAPTCHA integration to mautic 2.11 and newer.

Ideas and suggestions are welcome, feel free to create an issue or PR on Github.

Licensed under GNU General Public License v3.0.

## Installation via composer (preferred)
Execute `composer require koco/mautic-recaptcha-bundle` in the main directory of the mautic installation.

## Installation via .zip
1. Download the [master.zip](https://github.com/KonstantinCodes/mautic-recaptcha/archive/master.zip), extract it into the `plugins/` directory and rename the new directory to `MauticRecaptchaBundle`.
2. Clear the cache via console command `php app/console cache:clear --env=prod` (might take a while) *OR* manually delete the `app/cache/prod` directory.

## Configuration
Navigate to the Plugins page and click "Install/Upgrade Plugins". You should now see a "reCAPTCHA" plugin. Open it to configure site key and site secret.

![plugin config](/doc/config.png?raw=true "plugin config")

## Usage in Mautic Form
Add "reCAPTCHA" field to the Form and save changes.
![mautic form](/doc/form_preview.png?raw=true "Mautic Form with reCAPTCHA")
