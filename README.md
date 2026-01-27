# Cawl Online Payments

## Credit card

[![M2 Coding Standard](https://github.com/Worldline-Plugins/cawl-plugin-magento-creditcard/actions/workflows/coding-standard.yml/badge.svg?branch=develop)](https://github.com/Worldline-Plugins/cawl-plugin-magento-creditcard/actions/workflows/coding-standard.yml)
[![M2 Mess Detector](https://github.com/Worldline-Plugins/cawl-plugin-magento-creditcard/actions/workflows/mess-detector.yml/badge.svg?branch=develop)](https://github.com/Worldline-Plugins/cawl-plugin-magento-creditcard/actions/workflows/mess-detector.yml)

This is a module for the credit card (iFrame) Cawl payment solution.

This solution is also included into [main plugin for adobe commerce](https://github.com/Worldline-Plugins/cawl-plugin-magento).

### Change log:

### 1.1.25
- Improved exemptions capabilities related to exemption types (added: No challenge request)

### 1.1.24
- Remove Mealvouchers logo from checkout page when using "Hosted Checkout (redirect to Worldline)"
- Version 1.1.23 is skipped to make the CC module version equal to the CAWL module version

#### 1.1.22
- Fix: Do not allow usage of decimals in the object cardPaymentMethodSpecificInput.paymentProduct130SpecificInput.threeDSecure.numberOfItems

#### 1.1.21
- Fix issues with amount discrepancy feature

#### 1.1.20
- Added: Possibility to auto-include primary webhooks URL in the payload of payment request, and to configure up to 4 additional endpoints.

#### 1.1.19
- Improved: Data mapping to flag correctly exemptions requests to 3-D Secure.

#### 1.1.18
- Add new payment method: Pledg

#### 1.1.17
- Remove MealVouchers configuration from hosted checkout 
- Fix mobile payment method information not being shown in order details

#### 1.1.16
- Fix print invoice issue
- Update payment brand logos

### 1.1.15
- Allow order creation on amount discrepancies

### 1.1.14
- Add quote ID to request payload
- Fix wrong IP address being sent on checkout
- Decrease maximum payment method logos
- Add compatibility with 2.4.8-p2

### 1.1.13
- Fix issue with sending email

### 1.1.12
- Fix wrong handling of payment specific information on order page

#### 1.1.11
- Fix comma separated email validation in notification settings

#### 1.1.10
- Fix issue with showing split payment amounts on order details page for Mealvoucher transactions
- Fix issue with showing Mealvoucher in full redirect

#### 1.1.9
- Fix logo issue for CB on checkout page
- Fix PHP >= 8.2 issue with not sending parameter by reference

#### 1.1.8
- Add Mealvoucher payment product
- Add CVCO (Cheque Vacances Connect Online) payment product

#### 1.1.7
- Add compatibility with PHP 8.4
- Update SDK version

#### 1.1.6
- Update the core CAWL module to version 1.1.6

#### 1.1.5
- Update the core CAWL module to version 1.1.5

#### 1.1.4
- Update plugin translations

#### 1.1.3
- Added 3DS exemption types to the plugin

#### 1.1.2
- Update the core CAWL module to version 1.1.2

#### 1.1.1
- Updated payment gateway API base URL

#### 1.1.0
- Fixed validation for HTML template ID configuration. It is no longer required to have extension on HTML templates.

#### 1.0.0
- Initial version.
