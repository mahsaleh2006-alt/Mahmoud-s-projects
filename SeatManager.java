package Seat;

//**these are our imports 
//**"io" gives access to: File, FileWriter, PrintWriter etc..
//** while "util" gives access to: Scanner, ArrayList, List etc..**\\
import java.io.*;
import java.util.*;

public class SeatManager {
	private List<seat> seats;// ** this is the start of an array list where "seats" will store the collection
								// of "seat" objects**\\
	private final String filePath;// ** filePath will store the path to the data file while final is there to
									// make sure it is not changed later**\\

//** this is a new constructor that will store the provided file path and create an empty array list**\\
	public SeatManager(String filePath) {
		this.filePath = filePath;
		this.seats = new ArrayList<>();
	}

//**This method is going to read seat data from the text file, if the file does not exist then it will throw FileNotFoundException**\\
	public void load() throws FileNotFoundException {
		seats.clear();// **clears any existing entries to avoid duplicates**\\
		File f = new File(filePath);// ** here we created a new file object that will create a reference for the
									// file if the file doesn't exist stop and report the problem**\\
		if (!f.exists()) {
			throw new FileNotFoundException("Seat file not found: " + filePath);
		}
//**Created a new Scanner with a read loop that will continue reading the file until the text file ends and if the line is empty it will continue**\\
		Scanner fileScanner = new Scanner(f);
		while (fileScanner.hasNextLine()) {
			String line = fileScanner.nextLine().trim();
			if (line.isEmpty())
				continue;
//**Split one or more white spaces, starts filling in the parts of the Array list we are going to have 7 parts starting from 0 and ending at 6**\\
			String[] parts = line.split("\\s+");
			if (parts.length < 7)
				continue;
//**converts text into appropriate data types**\\
			String seatnum = parts[0];
			String seatclass = parts[1];
			Boolean window = Boolean.parseBoolean(parts[2]);
			Boolean aisle = Boolean.parseBoolean(parts[3]);
			Boolean table = Boolean.parseBoolean(parts[4]);
			double price;
//** here we are going to try turning the price in the text file into doubles since they are numbers and if they are an invalid input they re going to be caught and the price will be reset to 0**\\
			try {
				price = Double.parseDouble(parts[5]);
			} catch (NumberFormatException e) {
				price = 0.0;
			}
			String email = parts[6];
//** we are going to create a new seat object that will convert one line from the text file into a usable seat in the program while also adding to the array list**\\
			seat s = new seat(seatnum, seatclass, window, aisle, table, price, email);
			seats.add(s);
		}
		fileScanner.close();// **since we are done with reading the file we are going to close the
							// Scanner**\\
	}

//**save() is going to write the seats back into the file; it may throw IOException (input output exception)**\\
	public void save() throws IOException {
//**overwrites the text file and prepares to write to it**\\
		PrintWriter pw = new PrintWriter(new FileWriter(filePath, false));
//**Writes each seat in the format it originally used in the text file**\\
		for (seat s : seats) {
			pw.println(s.FileLine());
		}
		pw.flush();// **makes sure everything is written**\\
		pw.close();// ** Closes the file safely**\\
	}

//**return an unmodifiable view of the seats by calling upon helper method**\\
	public List<seat> getAllSeats() {
		return Collections.unmodifiableList(seats);
	}

//** this method is going to be used for when the user wants to find his reserved seat using the number so if he inputs "null" its going to tell him that its an invalid input and if he inputs an actual number then it will automatically be saved in the correct format which is e.g(1A)**\\
	public seat findBySeatNum(String seatnum) {
		if (seatnum == null)
			return null;
		for (seat s : seats) {
			if (s.getseatnum().equalsIgnoreCase(seatnum.trim()))
				return s;
		}
		return null;
	}

//**this method is going to be used for seat reservation it returns false if a seat is not found it also returns false if the seat is not free otherwise it will call seat.reserve(email) and returns true 
	public boolean reserveSeat(String seatnum, String email) {
		seat s = findBySeatNum(seatnum);
		if (s == null)
			return false;
		if (!s.Free())
			return false;
		s.reserve(email);
		return true;
	}

//** Finds a seat and returns false if not found or already free otherwise calls s.cancel and returns true**\\
	public boolean cancelseat(String seatnum) {
		seat s = findBySeatNum(seatnum);
		if (s == null)
			return false;
		if (s.Free())
			return false;
		s.cancel();
		return true;
	}

//** Here we are going to create a new array list and only add seats where (!s.Free())**\\
	public List<seat> getreservedseat() {
		List<seat> res = new ArrayList<>();
		for (seat s : seats) {
			if (!s.Free())
				res.add(s);

		}
		return res;
	}

//** this method is going to find the best matching seat according to the users preferences and will only consider free seats it will read the text file and read every line and try to match what the user chose**\\
	public List<seat> findMatchingSeats(String seatclass, Boolean wantwindow, Boolean wantaisle, Boolean wanttable,
			Double maxprice) {
		List<seat> matches = new ArrayList<>();
		for (seat s : seats) {
			if (!s.Free())
				continue;
			if (seatclass != null && !s.getseatclass().equalsIgnoreCase(seatclass))
				continue;
			if (wantwindow != null && wantwindow && !s.window())
				continue;
			if (wantaisle != null && wantaisle && !s.aisle())
				continue;
			if (wanttable != null && wanttable && !s.table())
				continue;
			if (maxprice != null && s.getseatprice() > maxprice)
				continue;
			matches.add(s);
		}
		return matches;
	}

//** This method is going to rank free seats by a calculated score +3 if the class matches, +1 each for window/aisle/table, +2 if seat price < max price**\\
	public List<seat> findnextbest(String seatclass, Boolean wantwindow, Boolean wantaisle, Boolean wanttable,
			Double maxprice) {
		List<seatscore> scored = new ArrayList<>();
		for (seat s : seats) {
			if (!s.Free())
				continue;
			int sc = 0;
			if (seatclass != null && s.getseatclass().equalsIgnoreCase(seatclass))
				sc += 3;
			if (wantwindow != null && wantwindow && s.window())
				sc += 1;
			if (wantaisle != null && wantaisle && s.aisle())
				sc += 1;
			if (wanttable != null && wanttable && s.table())
				sc += 1;
			if (maxprice != null && s.getseatprice() <= maxprice)
				sc += 2;
			if (sc > 0)
				scored.add(new seatscore(s, sc));
		}

		scored.sort((a, b) -> {
			int cmp = Integer.compare(b.score, a.score);
			if (cmp != 0)
				return cmp;

			return Double.compare(a.s.getseatprice(), b.s.getseatprice());
		});
//**Extracts seats from the scored list into final result**\\
		List<seat> result = new ArrayList<>();
		for (seatscore ss : scored)
			result.add(ss.s);
		return result;

	}

//**Helper inner class to store seats with their calculated scores**\\
	private static class seatscore {
		seat s;
		int score;

		seatscore(seat s, int score) {
			this.s = s;
			this.score = score;
		}
	}

}
