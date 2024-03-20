-- Create materialized view for minimum, maximum, and average rates per month
CREATE MATERIALIZED VIEW monthly_rates_view AS
SELECT 
    DATE_TRUNC('month', currency_date) AS month,
    MIN(currency_rate) AS min_rate,
    MAX(currency_rate) AS max_rate,
    AVG(currency_rate) AS avg_rate
FROM currency_data
GROUP BY DATE_TRUNC('month', currency_date);

-- Create today_rates_view
CREATE VIEW today_rates_view AS
SELECT 
    currency_symbol,
    currency_rate
FROM currency_data
WHERE currency_date = CURRENT_DATE;