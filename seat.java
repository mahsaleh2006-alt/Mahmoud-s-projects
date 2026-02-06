package Seat;

public class seat {
//**These are the seats attributes they are marked as private to protect them from being changed by the other classes they can only be accessed using the getters (Public methods)**\\ 
	private String seatnum;// ** Seat number e.g "1A"**\\
	private String seatclass;// **Seat class type (STD/1ST)**\\
	private boolean window; // **True if and only if the seat has a window**\\
	private boolean aisle; // **True if and only if the seat is next to the aisle**\\
	private boolean table;// **True if and only if the seat has a table**\\
	private double price; // **Ticket price for the seat**\\
	private String email;// **Email of reserving passenger or "free"**\\

	// ** Now we are going to create a new constructor,which is
	// a code that runs every time we create a new seat object and will fill it
	// up with the needed information from the text file and store them in the
	// attributes above**\\
	public seat(String seatnum, String seatclass, boolean window, boolean aisle, boolean table, double price,
			String email) {
		this.seatnum = seatnum;// **Assigns the given seat number to this objects seatnum**\\
		this.seatclass = seatclass;// **Assigns Seat type (STD/1ST)**\\
		this.window = window;// **Stores weather seat has a window**\\
		this.aisle = aisle;// **Stores if the seat is on the aisle**\\
		this.table = table;// **Stores weather the seat has a table**\\
		this.price = price;// **Stores seat price**\\
		this.email = (email == null ? "free" : email);// **if the email is "null" (meaning no reservation), store "free"
														// instead this will prevent errors**\\

	}

//** these public methods provides read only access to other classes so they can retrieve information when it is needed but they wont be able to change it**\\
	public String getseatnum() {
		return seatnum;
	}

	public String getseatclass() {
		return seatclass;
	}

	public boolean window() {
		return window;
	}

	public boolean aisle() {
		return aisle;
	}

	public boolean table() {
		return table;
	}

	public double getseatprice() {
		return price;
	}

	public String getemail() {
		return email;
	}

//** This is our free check method it will return "true" when the seat is free under certain conditions which are: if the email = "null" or when the word "free" is written in any form "Free" "FREE" or when its just a blank string**\\
	public boolean Free() {
		return email == null || email.trim().equalsIgnoreCase("free") || email.trim().isEmpty();
	}

//**this method will attempt to reserve a seat with an email, it will throw "IllegalArgumentException" if no valid email was entered, it will throw "IllegalStateException" if the seat is not free, otherwise it will reserve the seat by storing the given email**\\ 
	public void reserve(String email) {
		if (email == null || email.trim().isEmpty()) {
			throw new IllegalArgumentException("Email is required to reserve a seat.");
		}

		if (!Free()) {
			throw new IllegalStateException("Seat is already reserved."); 
		}
		this.email = email.trim();
	}

//**this is the cancel method this will turn the email field to "free" affectively canceling the booking and making the seat free**\\
	public void cancel() {
		this.email = "free";
	}

//** this formating method will show the user the same format that is in the text file its used when we want to save the data back into the text file**\\  
	public String FileLine() {
		return String.format("%s %s %s %s %s %.2f %s", seatnum, seatclass, Boolean.toString(window),
				Boolean.toString(aisle), Boolean.toString(table), price, (email == null ? "free" : email));
	}

	@Override
//**Finally we are going to use the override command to override the to String command to make a human readable version to show the user as we align each variable and if the seat is free it will output "FREE"**\\
	public String toString() {
		String status = Free() ? "FREE" : email;
		return String.format("%-3s %3s W:%-5s A:%-5s T:%-5s %6.2f %s", seatnum, seatclass, window, aisle, table, price,
				status);
	}
}
