import streamlit as st

# Configure page settings (title, icon, layout)
st.set_page_config(
    page_title="Flight frequency analysis",
      page_icon=":airplane:",
        
)

# Apply custom CSS for styling (center-aligning content)
st.markdown(
    """
    <style>
    .main{
    text-align: center;
    }
    </style>
    """,
    unsafe_allow_html=True
)

# Page title and author Details
st.markdown("<h1 style='text-align: center;'>🛫Flight Frequency Analysis at King Khalid International Airport</h1>", unsafe_allow_html=True)
st.markdown("<h3 style='text-align: center;'>Mahmoud Saleh - 103282-1140990</h3>", unsafe_allow_html=True)

st.markdown("---")

# Intro description of the dashboard
st.info(
    "This dashboard presents an analysis of flight frequency at King Khalid International Airport. It provides insights into various aspects of flight operations, including airline performance, hourly distribution, flight status, weather impact, and daily activity trends."
)

st.markdown("---")

# Dropdown menu for selecting visualizations
st.markdown("### 📊 Select a Visualization")
option = st.selectbox(
    "",
    ["Top Airlines by Departures",
     "Flight per hour",
     "Flights by Status",
     "Weather conditions (Excluding Fair)",
     "Daily Flight Activity"
     ]
)

# Display the selected visualization and insights
if option == "Top Airlines by Departures":
    col1, col2, col3 = st.columns([1, 2, 1])
    with col2:
        st.image("Outputs/top_airlines_departures.png")

        st.markdown("### 📍 Insights")
        st.info(
            "This chart shows the top airlines by number of departures from King Khalid International Airport. It highlights the most active airlines, which can be useful for understanding market share and passenger preferences."
        )
        st.markdown("---")
elif option == "Flight per hour":
    col1, col2, col3 = st.columns([1, 2, 1])
    with col2:
        st.image("Outputs/flights_by_hour.png")
        st.markdown("### 📍 Insights")
        st.info(
            "This graph illustrates the distribution of flight departures by hour of the day. It helps identify peak hours for flight activity, which can be crucial for airport operations and passenger planning."
        )
        st.markdown("---")
elif option == "Flights by Status":
    col1, col2, col3 = st.columns([1, 2, 1])
    with col2:
        st.image("Outputs/flights_by_status.png")
        st.markdown("### 📍 Insights")
        st.info(
            "This chart categorizes flights based on their status (e.g., on time, delayed, cancelled). It provides insights into the reliability of flight operations and can help identify areas for improvement in airport services."
        )
        
        st.markdown("---")
elif option == "Weather conditions (Excluding Fair)":
    col1, col2, col3 = st.columns([1, 2, 1])
    with col2:
        st.image("Outputs/flights_by_weather_condition.png")
        st.markdown("### 📍 Insights")
        st.info(
            "This chart shows the impact of weather conditions on flight operations, excluding days with fair weather."
        )
        st.markdown("---")
elif option == "Daily Flight Activity":
    col1, col2, col3 = st.columns([1, 2, 1])
    with col2:
        st.image("Outputs/flights_per_day.png")
        st.markdown("### 📍 Insights")
        st.info(
            "This chart presents the daily trend of flight departures, helping to identify patterns and seasonal variations."
        )
        st.markdown("---")