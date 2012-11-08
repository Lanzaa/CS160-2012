// Author: Curtis Davis

import java.applet.*;
import java.awt.*;
import java.io.*;
import java.net.URL;
import java.net.URLConnection;
import javax.swing.*;

 /**
This is a simple program that uses a dice.com URL address to find a job listing, goes into that job listing website, and scrapes the qualifications and requirements for the job from it. This is not a dynamic program and will currently only work for a given URL. This outputs to a file called output.txt with each requirement on a seperate new line.
*/
public class requirements extends Applet{
	public static void main(String[] args){
		try{
//The given URL
			URL address = new URL(
					"http://seeker.dice.com/jobsearch/servlet/JobSearch?op=300&N=0&Hf=0&NUM_PER_PAGE=30&Ntk=JobSearchRanking&Ntx=mode+matchall&AREA_CODES=&AC_COUNTRY=1525&QUICK=1&ZIPCODE=&RADIUS=64.37376&ZC_COUNTRY=0&COUNTRY=1525&STAT_PROV=0&METRO_AREA=33.78715899%2C-84.39164034&TRAVEL=0&TAXTERM=0&SORTSPEC=0&FRMT=0&DAYSBACK=30&LOCATION_OPTION=2&FREE_TEXT=Java&WHERE=San+Jose%2C+CA");
//Opens a connection with the given URL			
			URLConnection conn = address.openConnection();
			BufferedReader in = new BufferedReader(
					new InputStreamReader(conn.getInputStream()));
			String line;
			String originalURL;
			int firstQuotation = 0;
			int secondQuotation = 0;
// Searches for the job listing's URL within the given URL's web source code by searching 
// for the quotation marks after seeing a particular line of code.
			while(!(line = in.readLine()).contains("<div><a href=\"/jobsearch/servlet/")); {
				for (int i = 0; i < line.length(); i++) {
					if (line.charAt(i) == '"')
						if (firstQuotation == 0)
							firstQuotation = i;
						else
							secondQuotation = i;
					}
// Formats the URL to work as an input
			originalURL = "http://www.dice.com" + line.substring(firstQuotation+1, secondQuotation);
			originalURL = originalURL.replaceAll("amp;", "");
			}
			
			URL address2 = new URL(originalURL);
			URLConnection conn2 = address2.openConnection();
			BufferedReader in2 = new BufferedReader(new InputStreamReader(conn2.getInputStream()));

			String line2;
			String qualifications = "";
			PrintWriter out = new PrintWriter(new FileWriter("output.txt"), false);
// Finds the qualifications and requirements within the target web page's source code.
			while(!(line2 = in2.readLine()).contains("<p>")); {
				for (int i = 0; i < line2.length(); i++) 
				{
				if ( (line2.charAt(i) == '&') &&
					 (line2.charAt(i+1) == 'b') )
				for (int j = i+7; j < line2.length(); j++) 
				{
					if ( (Character.isLetterOrDigit(line2.charAt(j)))   || (line2.charAt(j) == ' ') )
						qualifications += line2.charAt(j);
					else
					{	
						out.write(qualifications + "\n"); 
						qualifications = "";
						break;
					}
					}
				}
			}
			out.close();

		 // Catches any exceptions from errors connecting to server.
		 }  catch(IOException e)  {
			System.err.println("error connecting to server.");
			e.printStackTrace();
		}
	}
}
