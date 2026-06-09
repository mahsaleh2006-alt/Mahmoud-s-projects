import pandas as pd

# --- STEP 1: LOAD MERGED DATA ---
# Load the merged dataset containing flight and weather data
merged_flights_weather_df = pd.read_csv('data/merged_flights_weather.csv')

# Display the first few rows of the dataset to verify loading
print(merged_flights_weather_df.head())

# --- STEP 2: COUNT ARRIVAL AND DEPARTURE FLIGHTS BY AIRLINE ---
# Select only flights that have departed
departures_df = merged_flights_weather_df[
    merged_flights_weather_df["status"] == "Departed"
]

# Count number of departures for each airline
airline_departures_count = departures_df["airline.name"].value_counts()

# Print the top 10 airlines by number of departures
print("\nTop Airlines by Departures:") 
print(airline_departures_count.head())

# --- STEP 3: SAVE THE SUMMARY TO A CSV FILE ---
# Save the top 10 airlines by departures to a CSV file for later use in the dashboard
airline_departures_count.head(10).to_csv('outputs/airline_departures_count.csv')
print("\n Top airlinessummary saved")