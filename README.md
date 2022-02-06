# FTX Account To Spread Sheet

A script to pull account summary and positions from FTX into a spread sheet.

Run the script in a local web server.

Data is displayed in an HTML table, can be easily pulled into Excel and Google Sheets.

Data can be pulled from FTX main account and sub accounts.

Can be customised to change tickers and data imported.

## HOW TO

## REQUIRED

PHP running on local machine.

How to install PHP here. Windows, Mac and others.
https://www.php.net/manual/en/install.php

## RUN THE SCRIPT AS A WEB PAGE ON LOCAL WEB SERVER

Download the index.php script and put it in a folder on your machine where you want to run it.

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

Change the web address to the root of your local web server that is running.

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

A1: The address provided by ngrok service like https://d399-185-99-252-211.ngrok.io

B1: Text as follows: ?arbitrary=

C1: Create a tick box using Menu: Data: Data Validation.

Add this formula to cell A2. Data will populate below. When you tick or untick the box, the data will be refreshed.

=IMPORTHTML(CONCATENATE(A1,B1,C1), "table", 1)

## License

[https://opensource.org/licenses/MIT](https://opensource.org/licenses/MIT)