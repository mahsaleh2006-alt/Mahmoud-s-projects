import pandas as pd
import matplotlib.pyplot as plt
import os

#Creates outpts folder if it doesn't exist to store generated visualizations
os.makedirs('outputs', exist_ok=True)

#Load merged dataset containing flight and weather data
df = pd.read_csv('data/merged_flights_weather.csv')

# --- Data Cleaning Block ---
# Ensure 'status' column is string type and handle missing labels
df['status'] = df['status'].astype(str)

# Removes invalid or undefined status values
invalid_labels = ['Unknown', 'nan', 'None', '']
df = df[~df['status'].isin(invalid_labels)]

# Display unique status values to verify cleaning
print("Current unique statuses:", df['status'].unique())
# Filter dataset to include only departed flights for specific visualizations
departures_df = df[df["status"] == "Departed"]

# Visualization 1: Top 10 Airlines departing from King Khalid International Airport

# Count Top 10 airlines by number of departures
top_airlines = departures_df["airline.name"].value_counts().head(10)

# Create bar chart for top airlines
top_airlines.plot(kind='bar')

# Add lables and formatting
plt.title('Top 10 Airlines departing from King Khalid International Airport')
plt.xlabel('Airline')
plt.ylabel('Number of Departures')
plt.xticks(rotation=45)

plt.grid(axis='y')

# Save and display the plot
plt.tight_layout()

plt.savefig('outputs/top_airlines_departures.png')

plt.show()

plt.clf()

# Visualization 2: Flight distribution by weather condition (excluding 'Fair')
# Exclude 'Fair' condition to focus on more impactful weather conditions
filtered = df[df['Condition']!= 'Fair']

# Count Weather conditions
weather_counts = filtered['Condition'].value_counts().head(10)

# Create bar chart for flight distribution by weather condition
plt.figure(figsize=(10, 6))
weather_counts.plot(kind='bar')

# Add labels and formatting
plt.title('Flight by weather condition')
plt.xlabel('weather condition')
plt.ylabel('Number of Flights')
plt.xticks(rotation=45)

plt.tight_layout()

plt.grid(axis='y')

# Save and display the plot
plt.savefig('outputs/flights_by_weather_condition.png')

plt.show()

plt.clf()

# Visualization 3: Number of flights by hour of the day
# Convert time column to datetime and extract hour for analysis
df['flight_time'] = pd.to_datetime(df['flight_time'])

df['hour'] = df['flight_time'].dt.hour

# Count Flights per Hour
hour_counts = df['hour'].value_counts().sort_index()

# Create line plot for number of flights by hour of the day
plt.figure(figsize=(10, 6))
plt.plot(hour_counts.index, hour_counts.values, marker='o')

# Add labels and formatting
plt.title('Number of Flights by Hour of the Day')
plt.xlabel('Hour of the Day')
plt.ylabel('Number of Flights')

plt.xticks(range(0, 24))

plt.grid(True)

plt.tight_layout()

# Save and display the plot
plt.savefig('outputs/flights_by_hour.png')

plt.show()
plt.clf()

# Visualization 4: Flight distribution by status
# Count flights by status
df['status'].value_counts().plot(kind='bar')

# Add labels and formatting
plt.title('Flights by status')
plt.xlabel('Status')
plt.ylabel('Number of Flights')

plt.grid(axis='y')
plt.xticks(rotation=30)

plt.tight_layout()

# Save and display the plot
plt.savefig('outputs/flights_by_status.png')

plt.show()
plt.clf()

# Visualization 5: Number of flights per day
# extract date from flight_time
df['date'] = pd.to_datetime(df['flight_time']).dt.date

# Count flights per day
df['date'].value_counts().sort_index().plot()

# Add labels and formatting
plt.title('Flights per day')
plt.xlabel('Date')
plt.ylabel('Number of Flights')

plt.grid(True)
plt.xticks(rotation=45)

plt.tight_layout()

# Save and display the plot
plt.savefig('outputs/flights_per_day.png')

plt.show()
plt.clf()