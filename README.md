# Introduction

:::danger
    This project is still a work in progress. This is not Production ready yet.
:::

To take a test drive of our SDK on the sandbox environment. 

Click here [BaseCommerce Developers Page](https://www.basecommerce.com/developers/) to register for a demo account. In order to use the SDK in the production environment, you will need to have an active Merchant Account with Base Commerce.

### Requirements

- PHP 7.2
- PHP OpenSSL Extension
- PHP JSON Extension

### SDK Dependencies

- [Nesbot Carbon](https://carbon.nesbot.com)
- [Array Helpers](https://github.com/jdrieghe/array-helpers)


### Installation

The SDK uses [composer](https://getcomposer.org) to manage its dependencies. So before using the SDK, make sure you have Composer installed on your machine. Run the below command to add the package to your project.

```bash
composer require greenlyst/basecommerce
``` 

Once the SDK is installed it is time to setup your credentials.

::: warning 

**WHAT IS DIFFERENT FROM THE SDK PROVIDED BY BASECOMMERCE?** 

- Uses Composer for easier integration
- Source Controlled to keep track of changes
- Validations for mandatory fields before a request is sent to BaseCommerce
- Refactored with methods attached to each domain
- Code Analyzed and Quality checked through [PHPInsights](https://phpinsights.com/)
:::