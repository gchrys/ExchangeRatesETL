# ExchangeRatesETL
This is a PHP CLI application that extracts data from the Open Exchange Rates API, transforms the data from USD base to EUR and loads it into a PostgreSQL database.

**Setup**

You should change the API key and database(postgre) connection info in the dummy env file that is in the root directory. 
Make sure that in your application the env file is not in a public directory.

**Running**

Use the following command without arguments to get today's rates.

```cli_command.php```

You can also use start_date and end-date arguments to get specific date rates.

```cli_command.php start_date end-date```

Example

```cli_command.php 2024-03-01 2024-03-31```


**Chrontab**

To run the script every day at 05:45

```45 5 * * * /usr/bin/php /path/to/your/cli_command.php```

Change the php path and the path to the project accordingly

**Notes**

The core of program, meaning the classes that handle the Extract, Transform and Load of the data are 3 abstract classes that are extended by specific ones. Those implement the code for extraction from the Open Exchange Rate API, the logic of transforming from USD base to EUR and finally loading the data in a PostgreSQL database.
Having this abstraction ensures that future changes can be made effortlessly and without interfering with the existing code. Error handling and testing has been done to ensure that the program will run as intended and if an error occurs, meaningful error messages are displayed.

Some notes about the dataset and the handling of the data:

It is probably the best practice to have the rates stored in cents, if the requirements are strict about the exact exchange rate of a currency but in this case they are stored in decimal(12,4) for simplicity.
Also the curl request is made without SSL, again for simplicity but it should be included in production.
