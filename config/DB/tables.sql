-- Create the currency_data table
CREATE TABLE currency_data (
    id SERIAL PRIMARY KEY,
    currency_date DATE NOT NULL,
    currency_symbol VARCHAR(3) NOT NULL,
    currency_rate DECIMAL(12, 4) NOT NULL,
    CONSTRAINT unique_currency_date_and_symbol UNIQUE (currency_date, currency_symbol)
);