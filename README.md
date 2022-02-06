# FTX Account To Spread Sheet

A script to pull account summary and positions from FTX into a spread sheet.

Provides quick access to data that is not shown on the FTX web site, including:

- total account position size (total of all futures and margin borrows)
- total account collateral (accounting for coin collateral fraction)
- total realized PnL for perps and futures

Works in Excel and Google Sheets.

Can be customised to change the account data and tickers imported.

Data can be pulled from main account and sub accounts.

The script runs in a local web server. Data is served in an HTML table, for quick import.

## HOW TO

## REQUIRED

PHP running on local machine.

How to install PHP here. Windows, Mac and others.
https://www.php.net/manual/en/install.php

## INSTALL CCXT

Get CCXT and install it where you want to run it.

https://docs.ccxt.com/en/latest/install.html

https://github.com/ccxt/ccxt

## RUN THE SCRIPT AS A WEB PAGE ON LOCAL WEB SERVER

Download index.php and account.php scripts and put them in folder above where you put CCXT.

Open a terminal / shell window and CD to the directory where you put the script.

$ cd /Users/mac/Desktop/folder/

Start the basic web server that is built–in to PHP using this command:

$ php -S localhost:8000

Leave the window running in the background.

Now test the script, it should run in a web browser at this address.

http://localhost:8000/

Loading this page should get data from the exchange and show it in the web browser.

## PULL DATA INTO MICROSOFT EXCEL VIA WEB QUERY

Tested on Mac Excel v16.57

Go To: Menu: Data: Get External Data: Run Web Query

Make a copy of the SampleWebQuery01.iqy file, or just modify the existing one.

Change the address to your local server that was set up.

http://localhost:8000/

## CREATE A TUNNEL FROM INTERNET TO THE LOCAL SERVER

Only necessary for Google Sheets, not needed for Excel.

Sign up for a free account at Ngrok.com

Start a tunnel with a terminal command like this:

$ ./ngrok http 8000

The window will show you the external address for example:

Forwarding https://eabb-89-38-69-33.ngrok.io -> http://localhost:8000

Leave this terminal window running in the background.

## PULL DATA INTO GOOGLE SHEETS VIA IMPORTHTML

In Google Sheets, create a new sheet and add the following cells:

In cell A1: The address provided by ngrok service like https://d399-185-99-252-211.ngrok.io

In cell B1: Type the following: ?a=

This adds an arbitrary variable to the URL, which can be changed to cause a refresh

In cell C1: Create a tick box using Menu: Data: Data Validation.

In cell A2: Add this formula: =IMPORTHTML(CONCATENATE(A1,B1,C1), "table", 1)

The data will populate starting from cell A2.

When you tick or untick the box, the data will be refreshed.

## License

[https://opensource.org/licenses/MIT](https://opensource.org/licenses/MIT)