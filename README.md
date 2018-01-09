# Mautic reCAPTCHA Plugin [![CircleCI](https://circleci.com/gh/KonstantinCodes/mautic-recaptcha.svg?style=svg)](https://circleci.com/gh/KonstantinCodes/mautic-recaptcha)
This Plugin brings reCAPTCHA integration to mautic 2.11 and newer.

BETA version. Ideas and suggestions are welcome, feel free to create an issue or PR on Github.

Licensed under GNU General Public License v3.0.

## Installation
1. Move the [MauticRecaptchaBundle](MauticRecaptchaBundle) directory of this repository to the `plugins/` directory.
2. Clear the cache via console command `php app/console cache:clear --env=prod` (might take a while) *OR* manually delete the `app/cache/prod` directory
3. Click "Install/Upgrade Plugins" in the Plugins menu.

You should now see a "reCAPTCHA" plugin. Open it to configure site key and site secret.

![plugin config](/doc/config.png?raw=true "plugin config")

## Usage in Mautic Form
Add "reCAPTCHA" field to the Form and save changes.
![mautic form](/doc/form_preview.png?raw=true "Mautic Form with reCAPTCHA")
