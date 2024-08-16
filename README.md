# Bank Application

Welcome to the BankApp! This project represents the final assignment for Codelex intensive bootcamp and has been developed using PHP in conjunction with the Laravel framework.

## Features

- **User Authentication**: Allows users to register and log in.
- **Two Account Typs**: Allows users create Transaction type accounts or Investment type accounts.
- **Multi-Currency Accounts**: Create and manage accounts in multiple currencies. Investment accounts are limited to a single currency (USD)
- **Money Transfers**: Facilitate transfers between Transaction accounts with automatic currency conversion.
- **Transaction History**: Access a record of transactions between accounts.
- **Investment Creation**: Facilitate investments in cryptocurrencies using funds from Investment accounts.
- **Investment History**: Access a record of investments and do followup on their individual profitability.
- **Investment Account To-Up**: Use Transaction Account funds to transfer money to Investment Accounts.
- **Cryptocurrency Management**: View and buy cryptocurrencies from the top 20 list using funds from investment accounts and CoinPaprika as crypto data API.
- **Wallet Management**: View cryptocurrency wallets, their profitability percentage and sell cryptocurrencies.

## Overview

The Bank Application is designed as a web-based platform that emulates a comprehensive banking system. Users can manage different currency accounts, execute transactions, and invest in a selection of cryptocurrencies.

## Demo

![Demo GIF](public/images/Demo.gif)

## Technologies Used

- PHP 8.2 or later
- Laravel 11.9 or later

## Installation

### Clone the Repository

```bash
git clone https://github.com/smitens/BANKINGAPP.git
```
### Install Dependencies

```bash
composer install
```
## Setup

### Set Up Environment File

```bash
copy the .env.example file to a new .env file
```

### Launch migration

```bash
php artisan migrate
```

### Install Frontend Dependencies

```bash
npm install
```

### Build Frontend Assets

```bash
npm run build
```

### Serve the Application

```bash
php artisan serv
```
