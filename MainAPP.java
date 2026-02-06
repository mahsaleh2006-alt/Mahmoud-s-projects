package Seat;

import java.io.IOException;//**the input/output exception is imported to deal with saving/loading exceptions while also dealing with red/write errors**\\
import java.util.List;//**Allows the creation of an arraylist that is going to store the seat data**\\
import java.util.Scanner;//**Allows us to ask inputs from the user**\\

public class MainAPP {
//** A "static" method which means that it will change depending on the device it is running on while "Final" means that the path wont be changed later on in the code and we are going to save the file path in "DATA_FILE" **\\ 
	private static final String DATA_FILE = "C:\\DATA\\seats-3.txt";

//**Program entry point**\\
	public static void main(String[] args) {
//** create a new seat manager with DATA_FILE then inside it will try to load the data file if it fails then it will catch exception and print the error message and stop the program**\\
		SeatManager manager = new SeatManager(DATA_FILE);
		try {
			manager.load();
			System.out.println("Loaded seat data from: " + DATA_FILE);
		} catch (IOException e) {
			System.out.println("Error Loading seat data: " + e.getMessage());
			return;
		}
//** create a new scanner that is going to let the user input what he wants**\\
		// **the "running" controls the menu loop**\\
		Scanner kb = new Scanner(System.in);
		boolean running = true;
//**repeats the menu until running becomes false**\\
		while (running) {
			printMenu();
			String pick = kb.nextLine().trim();// **stores trimmed user inputs**\\
			switch (pick.toUpperCase()) { // ** makes the switch case-insensetive for letters
			case "1": // ** cases call corresponding helper methods passing manager and the scanner kb
				doReserve(manager, kb); // **Q sets running false to exit loop**\\
				break;
			case "2":
				docancel(manager, kb);
				break;
			case "3":
				doview(manager, kb);
				break;
			case "Q":
				running = false;
				break;

			default:
				System.out.println("Invalid choice.");
			}
		}
//*After exiting the menu its going to attempt to save if it succeeds it will print a message but if it fails then it will print the error message**\\
		try {
			manager.save();
			System.out.println("Data saved. Goodbye!");
		} catch (IOException e) {
			System.out.println("Error saving file: " + e.getMessage());
		}

		kb.close();
	}

//**the menu helper method that we called upon earlier this will print our menu**\\
	private static void printMenu() {
		System.out.println();
		System.out.println("--Seat Booking System--");
		System.out.println("1 - Reserve Seat");
		System.out.println("2 - Cancel Seat");
		System.out.println("3 - View Seat Reservations");
		System.out.println("Q - Quit.");
		System.out.print("pick: ");
	}

//** the reserve method that we called upon earlier it gives the user the choices he has to make to be assigned a seat according to his preferences so if he wants 1st or std if he wants a window y/n etc..**\\
	private static void doReserve(SeatManager manager, Scanner kb) {
		System.out.println("Enter desired class (STD / 1ST): ");
		String cls = kb.nextLine().trim().toUpperCase();

		if (!(cls.equals("STD") || cls.equals("1ST"))) {
			System.out.println("Invalid class.");
			return;
		}

		System.out.print("want window? (Y/N): ");
		boolean wantwindow = yes(kb.nextLine());

		System.out.print("want aisle? (Y/N): ");
		boolean wantaisle = yes(kb.nextLine());

		System.out.print("want table? (Y/N): ");
		boolean wanttable = yes(kb.nextLine());

		System.out.print("Maximum price: ");
		double maxprice;

		try {
			maxprice = Double.parseDouble(kb.nextLine());
		} catch (Exception e) {
			System.out.print("invaild price.");
			return;
		}

		System.out.print("Enter email to reserve with: ");
		String email = kb.nextLine().trim();

		if (!isvalidEmail(email)) {
			System.out.println("Invalid email.");
			return;
		}
//**First its going to try to find an exact match**\\
		List<seat> exact = manager.findMatchingSeats(cls, wantwindow, wantaisle, wanttable, maxprice);
		if (!exact.isEmpty()) {
			seat chosen = exact.get(0);
			manager.reserveSeat(chosen.getseatnum(), email);
			System.out.println("Seat booked: " + chosen.getseatnum());
			return;
		}
//**if no exact match found then next best match will be shown**\\
		List<seat> nextbest = manager.findnextbest(cls, wantwindow, wantaisle, wanttable, maxprice);

		if (nextbest.isEmpty()) {
			System.out.println("No seats match your requirements.");
			return;
		}

		System.out.println("No match found. Showing alternatives");
		int show = Math.min(3, nextbest.size());

		for (int i = 0; i < show; i++) {
			System.out.printf("%d) %s%n", i + 1, nextbest.get(i).toString());
		}

		System.out.print("pick seat number or 0 to cancel: ");
		int option;

		try {
			option = Integer.parseInt(kb.nextLine());
		} catch (Exception e) {
			System.out.println("Invaild choice.");
			return;
		}

		if (option >= 1 && option <= show) {
			seat chosen = nextbest.get(option - 1);
			manager.reserveSeat(chosen.getseatnum(), email);
			System.out.println("Seat booked: " + chosen.getseatnum());
		} else {
			System.out.println("No reservation made.");
		}
	}
//**Seat cancellation**\\
	private static void docancel(SeatManager manager, Scanner kb) {

		System.out.println("Cancel by: 1) Seat number 2) Email");
		String choice = kb.nextLine().trim();

		if (choice.equals("1")) {
			System.out.print("Enter seat number: ");
			String seatnum = kb.nextLine().trim();

			seat s = manager.findBySeatNum(seatnum);

			if (s == null) {
				System.out.println("Seat not found.");
				return;
			}

			if (s.Free()) {
				System.out.println("Seat is already free.");
				return;
			}

			manager.cancelseat(seatnum);
			System.out.println("seat " + seatnum + "cancelled");
		} else if (choice.equals("2")) {

			System.out.print("Enter email: ");
			String email = kb.nextLine().trim();

			int count = 0;

			for (seat s : manager.getreservedseat()) {
				if (s.getemail().equalsIgnoreCase(email)) {
					manager.cancelseat(s.getseatnum());
					count++;
				}
			}

			System.out.println("Cancelled" + count + "seat(s)");
		} else {
			System.out.println("Invalid option");
		}

	}
//**Seat viewer**\\
	private static void doview(SeatManager manager, Scanner kb) {

		System.out.println("1) All seats  2) Reserved only");
		String pick = kb.nextLine().trim();

		if (pick.equals("1")) {
			for (seat s : manager.getAllSeats()) {
				System.out.println(s.toString());

			}
		} else {
			List<seat> reserved = manager.getreservedseat();
			if (reserved.isEmpty()) {
				System.out.println("No reserved seat.");
			} else {
				for (seat s : reserved) {
					System.out.println(s.toString());
				}
			}
		}

	}
//**Converts y/n into booleans**\\
	private static boolean yes(String s) {
		if (s == null)
			return false;
		s = s.trim().toUpperCase();
		return s.startsWith("Y");

	}
//**Email validation**\\
	private static boolean isvalidEmail(String e) {
		if (e == null)
			return false;
		return e.contains("@") && e.contains(".");
	}

}
