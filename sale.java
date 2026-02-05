package MAHMOUD_SALEH_103282_1140990;

/** we imported these commands so we can read and handle the file 
while also giving us the ability to ask the user for an input since it is needed to award the salesmen a reward**/
import java.util.Scanner;
import java.io.File;
import java.io.FileNotFoundException;

public class sale {
	/**
	 * we wrote throws FileNotFoundException to make sure that the file actually
	 * exists on the computer
	 **/
	public static void main(String[] args) throws FileNotFoundException {
		
		/**
		 * we declared these items with the final modifier so the amounts don't change
		 * and stay the same while also having double since it has a decimal point in
		 * them we use these to represent percentages
		 **/
		final double Base_pay = 70.0;
		final double Base_commission = 0.11;
		final double Imoroved_commission = 0.15;
		/**
		 * Here we used 2 scanners one to read the file so we can get the information
		 * from it the other to collect an input from the user
		 **/
		Scanner fileReader = new Scanner(new File("C:\\Users\\mahsa\\Desktop\\salesData-1.txt"));
		Scanner input = new Scanner(System.in);
		/**
		 * These 2 prints are for the header as asked by the question file to make the
		 * code look cleaner we made a helper method
		 **/
		printHeader();
		// Now we are going to start variables
		int salesmancount = 0; /**
								 * This is the sales man counter it will increase when the program reads a
								 * salesman using another command which will be shown later
								 **/
		/**
		 * These are arrays made to store the names of the salesmen as well as the total
		 * sales per salesman and the total quantity of the items they sold as we
		 * haven't started reading yet we put 10 so we don't run out of room in the
		 * array
		 **/
		String[] names = new String[10];
		double[] totals = new double[10];
		int[] items = new int[10];
		/**
		 * this will start reading the file line by line and continue until there are no
		 * more lines left and while reading the current line it will remove spaces that
		 * have no text in them to conserve storage
		 **/
		while (fileReader.hasNextLine()) {
			String line = fileReader.nextLine().trim();
			// this line will skip empty lines
			if (line.isEmpty())
				continue;
			/**
			 * Detect start of new sales man data And store current salesman name And add to
			 * the salesman counter
			 **/
			if (line.startsWith("Salesman")) {
				String salesman_name = line;
				salesmancount++;
				/**
				 * Again we are initializing the total sales for a sales man And the total items
				 * sold for a salesman
				 **/
				double total_sales = 0.0;
				int total_items = 0;
				// skip header and prioritize sales data
				if (fileReader.hasNext())
					fileReader.nextLine();

				while (fileReader.hasNextLine()) {
					String dataLine = fileReader.nextLine().trim();
					// Stop reading the file if there is an empty line or next sales man starts
					if (dataLine.isEmpty())
						break;
					if (dataLine.startsWith("Salesman")) {
						break;
					}
					/**
					 * This line splits the line by space into (item name, price and quantity) while
					 * also converting the value and quantity from string to int and double
					 **/
					String[] parts = dataLine.split("\\s+");
					if (parts.length >= 3) {
						double value = Double.parseDouble(parts[1]);
						double quantity = Double.parseDouble(parts[2]);
						/**
						 * This is where the calculation starts here we add the total sales by
						 * multiplying the value and quantity
						 **/
						total_sales += value * quantity;
						// creating a count for the total items
						total_items++;

					}

				}
				// we are storing the results in the array
				names[salesmancount - 1] = salesman_name;
				totals[salesmancount - 1] = total_sales;
				items[salesmancount - 1] = total_items;
				/**
				 * here we are printing the total sales for each sales man so the user can
				 * decide how much reward he wants to give
				 **/
				System.out.printf("Total Sales for %s are: %.0f QR%n", salesman_name, total_sales);

			}

		}
		// a summary for the total of salesmen found in the file
		System.out.println("\nA total of " + salesmancount + "salesmen record(s) found in the input file.\n");

		double reward = 0.0;
// Repeat until a valid input is given
		while (true) {
			System.out.println("Please Enter last weeks Reward ammoint (In QR): ");
// check if the user has entered a number
			if (input.hasNextDouble()) {
				reward = input.nextDouble();
// if a positive number is entered stop
				if (reward > 0) {
					break;
				} else {
					System.out.println("Please enter a positive number");

				}

			} else {
				System.out.println("invalid input please enter a number");
				input.next();
				// clears the wrong input
			}
		}

		// calculations to calculate the total earnings that i put in a helper method to
		// make the code look clean
		calculateErnings(salesmancount, names, totals, items, Base_pay, Base_commission, Imoroved_commission, reward);

		fileReader.close();
		input.close();

	}

	// the helper method mentioned before to print the header
	public static void printHeader() {
		System.out.println("$$$____ Sales Commissioning System ____$$$");
		System.out.println("Uploading sales data from C:\\\\Users\\\\mahsa\\\\Desktop\\\\salesData-1.txt file");
	}

	// the other main method mentioned that helps calculate the earnings
	public static void calculateErnings(int salesmancount, String[] names, double[] totals, int[] items,
			double Base_pay, double Base_commission, double Improved_commission, double reward) {

		for (int i = 0; i < salesmancount; i++) {
			double rate = (items[i] >= 5) ? Improved_commission : Base_commission;
			double commission = totals[i] * rate;
			double total_ernings = Base_pay + commission + reward;

			System.out.printf("The Net Earnings (with %.0f%% commission) for %s are: %.1f " + "(%.0f + %.1f + %.0f)%n",
					rate * 100, names[i], total_ernings, Base_pay, commission, reward);

		}
	}

}
