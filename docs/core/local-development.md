# Setting Up Payflow For Local Development

## Overview

This guide is here to help you set-up Payflow locally so you can contribute to the core and admin hub.

## Before your start

You will need a Laravel application to run Payflow in.

## Set-Up

In the root folder of your Laravel application, create a "packages" folder.

```sh
mkdir packages && cd packages
````

Add the "packages" folder to your `.gitignore` file so the folder is not committed to your Git repository.

```
...
/.idea
/.vscode
/packages
```

Fork and then clone the [monorepo](https://github.com/payflowphp/payflow) to the `packages` folder, e.g. `/packages/payflow/`.

```sh
git clone https://github.com/YOUR-USERNAME/payflow
````

Update your `composer.json` file similar to the following.

```json
    "repositories": [{
        "type": "path",
        "url": "packages/*",
        "symlink": true
    }],

    "require": {
        "payflowphp/payflow": "*",
    }
````

Ensure minimum stability is set for development
```json
    "minimum-stability": "dev",
````

Run `composer update` from your Laravel application's root directory and fingers crossed you're all up and running,. 

```sh
composer update
````

## Done
You can now follow the Payflow installation process and start contributing.
