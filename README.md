# Ftx Account To Sheet

A web server script to pull in account and position data from FTX exchange.

Selected data is displayed in an HTML table, wich can be easily pulled into Google Sheets.

Data is pulled from main account and sub accounts.

## HOW TO

## REQUIREMENT

PHP running on local machine.

RUN THE SCRIPT AS A WEB PAGE ON LOCAL WEB SERVER

Open a Terminal / shell window and CD to the directory where you want to run this script.
$ cd /Users/mac/Desktop/folder/

Start PHP built in web server using this command:
$ php -S localhost:8000
Leave this terminal window running in the background.

Now test the script, it should run in a web browser at this address.
Loading the page should get data from the exchange and print it out in the page:
http://localhost:8000/

## CREATE A TUNNEL FROM INTERNET TO THE LOCAL SERVER

Now sign up for a free account at Ngrok.com
Start a tunnel with a terminal command like this:
$ ./ngrok http 8000
The window will show you the external address for example:
Forwarding https://eabb-89-38-69-33.ngrok.io -> http://localhost:8000
Leave this terminal window running in the background.

## INTO GOOGLE SHEETS VIA IMPORTHTML

In Google Sheets, create a new sheet and add the following cells:
A1: The address provided by ngrok service like https://d399-185-99-252-211.ngrok.io
B1: Text as follows: ?arbitrary=
C1: Create a tick box using Menu: Data: Data Validation.

Add this formula to cell A2. Data will populate below. When you tick or untick the box, the data will be refreshed.
=IMPORTHTML(CONCATENATE(A1,B1,C1), "table", 1)

## INTO MICROSOFT EXCEL VIA WEB QUERY

Tested on Mac Excel v16.57
Go To: Menu: Data: Get External Data: Run Web Query
Make a copy of the SampleWebQuery01.iqy file and modify it to add the Ngrok address

## License

[https://opensource.org/licenses/MIT](https://opensource.org/licenses/MIT)