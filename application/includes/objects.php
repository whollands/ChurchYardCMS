<?php if(!defined("ChurchYard_Execute")) die("Access Denied.");

/* ------------------------------------------------------------------------ /*

    Church Yard Content Management System
    Copyright (C) 2016 Will Hollands
    <http://hollands123.com/projects/churchyardcms/>

    For AQA NEA A-Level Project
    Designed for St. Peter's Church, Rendcomb.

    Released under GNU Public License
    ---------------------------------
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
    ---------------------------------

	For Help with Configuration please see
	<http://hollands123.com/projects/churchyardcms/support/config>

/* ------------------------------------------------------------------------ */

class Database
{

    private static $Conn;
   
    public static function Connect() 
    {      
        if(!isset(self::$Conn))
        {   
        	$Config = include("config/database.php");
        	// Load config from database file
        	// encapsulated within Connect() function

        	try
        	{
			  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			  // enable mysqli to throw exceptions

        	  self::$Conn = new mysqli($Config->Host, $Config->Username, $Config->Password, $Config->DatabaseName);
        	  // create connection
        	}

        	catch (Exception $e)
	    	{
	    		Server::ErrorMessage($e->getMessage());
	    	}
        }
        return self::$Conn;
    }

    public static function Disconnect()
    {
    	self::$Conn->close();
    	// use the default MySQL close() function
    }

    public static function Query($SQL)
    {
    	try
    	{
	        // Connect to the database
	        $Conn = self::Connect();

	        // Query the database
	        $Result = $Conn->Query($SQL);
    	}
    	catch (Exception $e)
    	{
    		Server::ErrorMessage($e->getMessage());
    	}

        return $Result;
    }

    public static function Select($SQL)
    {
        $Data = array();

        $Result = self::Query($SQL);

        if($Result === false)
        {
            return false;
        }

        while ($Row = $Result->fetch_assoc())
        {
            $Data[] = $Row;
        }
        // return the result of the query as an array.

        return $Data;
    }

    public static function Error() 
    {
    	$Conn = self::Connect();
        return $Conn->error;
    }

    public static function Filter($value)
    {
        $Conn = self::Connect();
        return "'" . $Conn->real_escape_string($value) . "'";
    }
}

Class Server
{
	//private static $Config = include("config/general.php");

	public static function GetFullPath()
	{
		
	}

	public static function GetPageURL()
	{
		$URL = $this->Config->URL->Base . $File;

		if($CleanURLs)
		{
			$URL = $Base . "/index.php" . $File;
		}

		return $URL;
	}

	public static function GetResourceURL($File = "")
	{
		return $Base . $File;
	}

	public static function Redirect($Location = "", $StatusCode = 303)
	{
	   header('Location: ' . GetPageURL($Location), true, $StatusCode);
	   exit;
	   // redirect a browser to the location specified, default is the homepage
	   // default error code is set to 303 for application redirect
	}

	public static function ErrorMessage($Message = 'Unknown error occurred')
	{
	    include("templates/error/header.php");

	    if($GLOBALS["Config"]->Dev->EnableDebug)
	    {
	        echo $Message;
	        // show detailed error message if enabled in the config file
	    }
	    else
	    {
	        echo "An Error Occurred While Proccessing Your Request.";
	        // show generic error message if enabled in the config file
	    }

	    include("templates/error/footer.php");
	    exit;
	}

	public static function Error404()
	{
		include("templates/error/header.php");
		echo "Error 404: Page not found.";
		include("templates/error/footer.php");
	    exit;
	}

	public static function OutputMessage($Message)
	{
		$_SESSION['Message'] = $Message;
	}

	public static function DisplayMessage()
	{
		$Message = $_SESSION['Message'];
		unset($_SESSION['Message']);
		return $Message;
	}
}

Class Jobs
{
	private static $SitemapFile = "sitemap.xml";
	private static $RobotsFile = "robots.txt";
	private static $CacheDirectory = "cache/";

	private function WriteFile($FileName, $Data)
	{
		try
		{
			$File = @fopen($FileName, 'w');
			// @ ignores errors and allows custom exeption handler to function

			if($File == false)
			{
				throw new Exception('Failed to open/create $FileName');
			}
			
			if(@fwrite($File, $Data) == false)
			// @ ignores errors and allows custom exeption handler to function
			{
				throw new Exception('Failed to write $FileName');
			}
		}
		catch (Exception $e)
		{
			Server::ErrorMessage($e->getMessage());
		}
	}

	public static function GenerateSitemap()
	{

		$XML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
				<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
				";

		$SQL = "SELECT URL, LastEdited
				FROM Pages
				ORDER BY PageOrder";

		$Data = Database::Select($SQL);

		foreach($Data as $Page)
		{
			$XML .= "<url>";
			$XML .= "<loc>" . $Page['URL'] . "</loc>";
			$XML .= "<lastmod>" . $Page['LastEdited'] . "</lastmod>";
			$XML .= "</url>";
		}

		$XML .= "</urlset>";

		self::WriteFile(self::$SitemapFile, $XML);
	}

	public static function GenerateRobots()
	// creates a robots.txt file
	{
		$Date = date('d-m-Y H:i:s');

		$Data = "# Auto-generated on $Date";
		$Data .= "\nUser-agent: *";
		$Data .= "\nDisallow: application/";
		$Data .= "\nDisallow: config/";
		$Data .= "\nDisallow: templates/";
		$Data .= "\nDisallow: cache/";

		self::WriteFile(self::$RobotsFile, $Data);
	}

	public static function ClearCache()
	{
		// foreach (new DirectoryIterator('cache/') as $fileInfo) 
		// {
		//     if(!$fileInfo->isDot())
		//     {
		//         unlink($fileInfo->getPathname());
		//     }
		// }
	}
}

Class User
{
	public static $UserID = null;
	public static $Username = null;
	public static $Name = "Guest";
	public static $EmailAddress = null;
	public static $IsLoggedin = false;
	public static $IsAdmin = false;

	private static function GetUserSalt($Username)
	{
		$UserSalt = null;

		if(!preg_match("/^[\w.]*$/", $Username))
		{
			Server::ErrorMessage("Username can only contain alphanumeric, periods and underscores");
		}
		else
		{
			$Username = Database::Filter($Username);
			// Prevent injection

			$Data = Database::Select("SELECT Salt FROM Users WHERE Username=$Username");
			// Execute

			$UserSalt = $Data[0]['Salt'];
		}

		return $UserSalt;
	}

	public static function CheckUserExists($UserID)
	{
		$Found = false;

		if(!is_pos_int($UserID))
		{
			Server::ErrorMessage("User ID must be a positive integer");
		}

		$UserID = Database::Filter($UserID);
		// prevent injection

		$SQL = "SELECT UserID FROM Users WHERE UserID=$UserID";
		$Data = Database::Select($SQL);
		// query database

		if(count($Data) == 1)
		{
			$Found = true;
		}
		
		return $Found;
	}

	public static function CheckUsernameExists($Username)
	{
		$Found = false;

		$Username = Database::Filter($Username);

		$SQL = "SELECT Username
				FROM Users
				WHERE Username=$Username
				";

		$Data = Database::Select($SQL);

		if(count($Data) == 1)
		{
			$Found = true;
		}

		return $Found;
	}

	public static function GetUserID()
	{
		return self::$UserID;
	}

	public static function GetUsername()
	{
		return self::$Username;
	}

	public static function CheckCredentials($Username, $Password)
	{
		if(!preg_match("/^[\w.]*$/", $Username))
		{
			Server::ErrorMessage("Username can only contain alphanumeric, periods and underscores");
		}

		$UserSalt = self::GetUserSalt($Username);
		// use object function to retrive user salt

		$MasterSalt = $GLOBALS["Config"]->MasterSalt;
		// get master salt from configuration

		$Password =  md5($MasterSalt . $Password . $UserSalt);
		// hash password

		$Username = Database::Filter($Username);
		$Password = Database::Filter($Password);
		// Prevent injection on input

		$SQL = "SELECT UserID, Name, EmailAddress, IsAdmin, Username FROM Users WHERE Username=$Username AND Password=$Password";
		$Data = Database::Select($SQL);
		// Prepare and execute statement
		
		if(Count($Data) == 1)
	    // if user is found in database
		{

			self::$UserID = $Data[0]['UserID'];
			// We know 1 record was found, so $Data[0] refers to the first record in the array
			// Prevent injection

			self::$Name = $Data[0]['Name'];
			self::$EmailAddress = $Data[0]['EmailAddress'];
			self::$Name = $Data[0]['Name'];

			self::$IsLoggedin = true;

			self::CreateSession(self::$UserID);
			// create session to keep user signed in

			$UserID = Database::Filter($UserID);

			Database::Query("UPDATE Users SET LastLogin=NOW() WHERE UserID=$UserID");
			// update last logged in time in user table.

			Server::OutputMessage(AlertInfo('Welcome back ' . User::$Name));

			return true;
			// user is authenticated
		}
		else
		{
			return false;
			// false means username and/or password incorrect
		}

	}

	private static function CreateSession($UserID)
	{
		if(!is_pos_int($UserID))
		{
			Server::ErrorMessage("User ID must be an integer");
		}
		else
		{

			$RandomToken = GetRandomToken();
			// create a new random session token

			$UserID = Database::Filter($UserID);
			$SessionToken = Database::Filter($RandomToken);
			$IPAddress = Database::Filter($_SERVER['REMOTE_ADDR']);
			// Prevent injection

			$SQL = "INSERT INTO Sessions (UserID, SessionToken, IP) VALUES ($UserID, $SessionToken, $IPAddress)";
			Database::Query($SQL)or Server::ErrorMessage(Database::Error());
			// insert session into database
			// or die with error message

			setcookie("CYCMS_SessionToken", $RandomToken, time() + (3600 * 24 * 30), "/");
			// save session token to user's computer
		}
	}

	public static function ChangePassword($UserID, $NewPassword)
	{
		$Result = false;
		// assume changing password failed, unless otherwise.

		if(!is_pos_int($UserID))
		// check user id is a positive integer
		{
			Server::ErrorMessage("User ID must be a positive integer");
		}
		else
		{
			if(!User::CheckUserExists($UserID))
			// check if user exists
			{
				Server::ErrorMessage("User ID $UserID does not exist.");
			}
			else
			{
				$UserSalt = GetRandomToken();
				// generate a new random user salt

				$MasterSalt = $GLOBALS["Config"]->MasterSalt;

				$NewPasswordHash =  md5($MasterSalt . $NewPassword . $UserSalt);
				// create a hash for the password

				$NewPasswordHash = Database::Filter($NewPasswordHash);
				$UserSalt = Database::Filter($UserSalt);
				$UserID = Database::Filter($UserID);
				// prevent injection

				$SQL = "UPDATE Users 
						SET Password=$NewPasswordHash, Salt=$UserSalt
						WHERE UserID=$UserID
						";

				if(Database::Query($SQL) == true)
				{
					$Result = true;
				}
			}
		}
		return $Result;
	}

	public static function CheckAuthenticated()
	{
		$SessionToken = Database::Filter($_COOKIE["CYCMS_SessionToken"]);

		$SQL = "SELECT UserID FROM Sessions WHERE SessionToken=$SessionToken";
		$Data = Database::Select($SQL);
		// Execute

		if(count($Data) == 1)
		{
			self::$IsLoggedin = true;

			$UserID = Database::Filter($Data[0]['UserID']);

			$SQL = "SELECT UserID, Username, Name, EmailAddress, IsAdmin FROM Users WHERE UserID=$UserID";
			$Data = Database::Select($SQL)or Server::ErrorMessage(Database::Error());

			self::$UserID = $Data[0]['UserID'];
			self::$Username = $Data[0]['Username'];
			self::$Name = $Data[0]['Name'];
			self::$EmailAddress = $Data[0]['EmailAddress'];
			self::$IsAdmin = $Data[0]['IsAdmin'];
		}
		else
		{
			Server::Redirect('login');
		}
	}

	public static function Logout()
	{
		
		$SessionToken = Database::Filter($_COOKIE["CYCMS_SessionToken"]);

		$Data = Database::Query("DELETE FROM Sessions WHERE SessionToken=$SessionToken")or Server::ErrorMessage(Database::Error());
		// Execute

		setcookie("CYCMS_SessionToken", "", time() -3600);
	}

	public static function DeleteSession($SessionID)
	{
		if(!is_pos_int($SessionID))
		{
			Server::ErrorMessage("Session ID must be an integer");
		}
		else
		{	
			$UserID = Database::Filter(User::$UserID);
			$SessionID = Database::Filter($SessionID);

			$SQL = "DELETE FROM Sessions WHERE SessionID=$SessionID AND UserID=$UserID";
			Database::Query($SQL)or Server::ErrorMessage(Database::Error());
		}
	}

	public static function CreateUser($Name, $Username, $EmailAddress, $Password, $IsAdmin)
	{
		$Name = Database::Filter($Name);
		$Username = Database::Filter($Username);
		$EmailAddress = Database::Filter($EmailAddress);
		$IsAdmin = Database::Filter($IsAdmin);

		$SQL = "INSERT INTO Users (UserID, Name, Username, EmailAddress, Password, Salt, $IsAdmin)
				VALUES (DEFAULT, $Name, $Username, $EmailAddress, $Password, $RandomToken, $IsAdmin)
				";

				User::ChangePassword($UserID, $Password);
	}

	public static function Update()
	{

	}

	public static function Delete($UserID)
	{
		if(!is_pos_int($UserID))
		{
			Server::ErrorMessage("User ID must be a positive integer");
		}
		else if(!self::CheckUserExists($UserID))
		{
			Server::ErrorMessage("User does not exist.");
		}
		else if($UserID == 0)
		{
			Server::ErrorMessage("Cannot delete the SuperUser (User ID 0)");
		}
		else
		{
			$UserID = Database::Filter($UserID);

			$SQL = "DELETE FROM Users WHERE UserID=$UserID";
			Database::Query($SQL)or Server::ErrorMessage(Database::Error());
			
		}
	}
}

Class Page
{
	// private $Base = $GLOBALS['Config']->URL->Base;
	// private $CleanURLs = $GLOBALS['Config']->URL->CleanURLs;

	public function DisplayContent()
	{
		

		$Path = GetCurrentPath();

		$PageURL = implode("/", $Path["call_parts"]);

		if($PageURL == null)
		{
		    $PageURL = "homepage";
		    // load homepage if no URL specified
		}

		$PageURL = Database::Filter($PageURL);

		$Data = Database::Select("SELECT PageName, Content FROM Pages WHERE URL=$PageURL");


		if(count($Data) != 1)
		{
		  IncludeScript("errors/404Error.php");
		  exit;
		}
		else
		{
		  $PageName = $Data[0]['PageName'];
		  $PageContent = $Data[0]['Content'];
		}

		include("templates/mainsite/header.php");

		echo $PageContent;

		include("templates/mainsite/footer.php");

	}

	public function DisplayNavigation()
	{

	}

	public function Delete($PageID)
	{
		if(!is_pos_int($PageID))
		{
			Server::ErrorMessage("Page ID must be an integer");
		}
		else
		{
			
			$PageID = Database::Filter($PageID);
			$SQL = "DELETE FROM Pages WHERE PageID=$PageID";
			Database::Query($SQL)or Server::ErrorMessage(Database::Error());
			
		}
	}

}

Class Media
{
	public function GetURLByID()
	{

	}

	static public function CheckMediaExists($MediaID)
	{
		
	}

	public function UploadMedia()
	{

	}	

	public function DeleteMedia($MediaID)
	{
		if(!is_pos_int($MediaID))
		{
			Server::ErrorMessage('MediaID must be a positive integer');
		}
		else
		{
			$MediaID = Database::Filter($MediaID);
			// filter is used TWICE below...

			$SQL = "SELECT MediaID, URL
					FROM Media
					WHERE MediaID=$MediaID
					";

			$Data = Database::Select($SQL);

			if(count($Data) != 1)
			{
				Server::ErrorMessage('Media File does not exist. (Media ID: $MediaID)');
			}
			else
			{
				try
				{
					if(!@unlink($Data[0]['URL']))
					{
						throw new Exception('Could not delete file from file system.');
					}

					// media ID has already been filtered

					$SQL = "DELETE FROM Media
							WHERE MediaID=$MediaID
							";

					if(!@Database::Query($SQL))
					{
						throw new Exception('Could not delete media from database.');
					}
				}
				catch (Exception $e)
				{
					Server::ErrorMessage($e->getMessage());
				}
			}
		}
	}

	public function ModifyMedia($MediaID, $MediaName)
	{

	}
}

Class Grave
{

	public static function CheckGraveExists($GraveID)
	{
		$Found = false;

		if(!is_pos_int($GraveID))
		{
			Server::ErrorMessage("Grave ID must be a positive integer");
		}

		$GraveID = Database::Filter($GraveID);
		// prevent injection

		$SQL = "SELECT GraveID FROM Graves WHERE GraveID=$GraveID";
		$Data = Database::Select($SQL);
		// query database

		if(count($Data) == 1)
		{
			$Found = true;
		}
		
		return $Found;
	}

	public static function CheckCoordsExist($XCoord, $YCoord)
	{
		$Found = false;

		if(!is_pos_int($XCoord) || !is_pos_int($YCoord))
		{
			Server::ErrorMessage("Co-ordinates must be a positive integer");
		}

		$XCoord = Database::Filter($XCoord);
		$YCoord = Database::Filter($YCoord);
		// prevent injection

		$SQL = "SELECT GraveID FROM Graves WHERE XCoord=$XCoord AND YCoord=$YCoord";
		$Data = Database::Select($SQL);
		// query database

		if(count($Data) == 1)
		{
			$Found = true;
		}
		
		return $Found;
	}

	public static function CreateGrave($GraveID, $XCoord, $YCoord, $Type)
	{
		if(!is_pos_int($GraveID))
		{
			Server::ErrorMessage('GraveID must be a positive integer');
		}
		else
		{
			$SQL = "INSERT INTO Graves (GraveID, XCoord, YCoord, Type)
					VALUES ($GraveID, $XCoord, $YCoord, $Type)
					";
		}
	}
	public static function UpdateGrave()
	{
		
	}
	public static function DeleteGrave()
	{
		
	}

}

Class Record
{

	public function GetRecord($RecordID)
	{
		if(!is_pos_int($RecordID))
		{
			Server::ErrorMessage("Record ID must be an positive integer");
		}
		else
		{
			try
			{
				
				$RecordID = Database::Filter($RecordID);
				$SQL = "SELECT * FROM Records WHERE RecordID=$RecordID";
				$Data = Database::Select($SQL);
			}
			catch (Exception $e)
			{
				Server::ErrorMessage($e->getMessage());
			}
			
			if(count($Data) != 1)
			{
				Server::ErrorMessage("Record not found.");
			}
			else
			{


			echo "<h1>" . $Data[0]['FirstName'] . " " . $Data[0]['LastName'] . "</h1>";

				echo "<strong>FirstName: </strong>" . $Data[0]['FirstName'] . "
			    <br><strong>LastName: </strong>" . $Data[0]['LastName'] . "
			    <br><strong>Date Of Birth: </strong>" . ConvertDate($Data[0]['DateOfBirth']) . "

			  <br><strong>Date Of Death: </strong>" . ConvertDate($Data[0]['DateOfDeath']) . "
			  <br><strong>Mother: </strong>" . $Data[0]['MotherID']
			  ;

			  switch($Data[0]['Gender'])
			  {
			  	default: echo "<p><i class=\"fa fa-question\"> Unknown Gender</i></p>"; break;
			  	case "m": echo "<p><i class=\"fa fa-male\"> Male</i></p>"; break;
			  	case "f": echo "<p><i class=\"fa fa-female\"> Female</i></p>"; break;
		  	  
		  	  }

		  	  echo Button("View Family Tree", GetPageURL("database/tree/" . $Data[0]['RecordID']));

		  	  echo "<p>&nbsp;</p>";
		  	}

		  
		}
	}

	public function CheckRecordExists($RecordID)
	{
		$Found = false;

		if(!is_pos_int($RecordID))
		{
			Server::ErrorMessage("Record ID must be a positive integer");
		}

		$RecordID = Database::Filter($RecordID);
		// prevent injection

		$SQL = "SELECT RecordID FROM Records WHERE RecordID=$RecordID";
		$Data = Database::Select($SQL);
		// query database

		if(count($Data) == 1)
		{
			$Found = true;
		}
		
		return $Found;
	}

	public function CreateRecord()
	{

	}

	public static function UpdateRecord()
	{
		
	}

	public static function DeleteRecord()
	{

	}
}

Class Map
{
	public static function DisplayMap()
	{
		$SQL = "SELECT * 
				FROM Graves 
				ORDER BY YCoord ASC, XCoord ASC
				";
		$Data = Database::Select($SQL);
		
		if(count($Data) == 0)
		{
			echo AlertWarning('No records found.');
		}
		else
		{
			echo "<table>";

			$CurrentRecordPointer = 0;

			for($Row = 0; $Row <= 10; $Row++)
			{
				echo "<tr>";

				for($Col = 0; $Col <= 20; $Col++)
				{
					if($Data[$CurrentRecordPointer]['XCoord'] == $Col && $Data[$CurrentRecordPointer]['YCoord'] == $Row)
					{
						switch($Data[$CurrentRecordPointer]['Type'])
						{
							default: case "h":
							echo "<td>";
							echo "<a href=\"" . GetPageURL('database/map/' . $Data[$CurrentRecordPointer]['GraveID']) . "\">";
							echo "<div class=\"content\"></div>";
							echo "</a>";
							echo "</td>";
							break;
						}

						$CurrentRecordPointer++;
					}
					else
					{
						echo "<td></td>";
					}
				}

				echo "</tr>";
			}
			echo "</table>";
		}
	}

	public static function GetRecordsInGrave($GraveID)
	{
		if(!is_pos_int($GraveID))
		{
			Server::ErrorMessage("Record ID must be a positive integer");
		}
		else
		{

			$GraveID = Database::Filter($GraveID);
			$SQL = "SELECT * FROM Records WHERE GraveID=$GraveID";
			$Data = Database::Select($SQL);

			if(count($Data) > 0)
			{
				echo "<h1>People buried in Grave " . $GraveID . "</h1>";

				foreach ($Data as $Record)
				{
					Record::GetRecord($Record['RecordID']);
				}
			}
			else
			{
				echo AlertWarning("No one is buried in this grave.");
			}
		}
	}

}

Class FamilyTree
{
	public static function DisplayTree($RecordID)
	{
		if(!is_pos_int($RecordID))
		{
			Server::ErrorMessage("Record ID must be a positive integer");
			// output error exception if record id is not a integer
		}
		else
		{
			$RecordID = Database::Filter($RecordID);
			// prevent injection

			$SQL = "SELECT RecordID, FirstName, LastName FROM Records WHERE RecordID=$RecordID";
			$Data = Database::Select($SQL);
			// query database

			if(count($Data) == 1)
			{
				echo "<ul>";
				echo "<li>";
				echo "<a href=\"" . GetPageURL('database/view/' . $Data[0]['RecordID']) . "\">";
				echo $Data[0]['FirstName'] . "</a>";

				self::DisplayAllChildren($Data[0]['RecordID']);

				echo "</li>";
				echo "</ul>";
			}
			else
			{
				Server::ErrorMessage("Record not found.");
			}
		}
	}

	private static function DisplayAllChildren($RecordID)
	{
		if(!is_pos_int($RecordID))
		{
			Server::ErrorMessage("Record ID must be a positive integer");
			// output error exception if record id is not a integer
		}
		else
		{

			$RecordID = Database::Filter($RecordID);

			$SQL = "
					SELECT RecordID, MotherID, FatherID, SpouseID, FirstName, LastName
					FROM Records
					WHERE MotherID=$RecordID
					OR FatherID=$RecordID
				   ";
			// query database to find all children of record

			$Data = Database::Select($SQL);
			// query database

			if(count($Data) > 0)
		    // if more than 1 record found
			{
				echo "<ul>";
				// create new sublist

				foreach ($Data as $Record)
				// for each child
				{
					echo "<li>";
					echo "<a href=\"" . GetPageURL('database/view/' . $Record['RecordID']) . "\">";
					echo $Record['FirstName'] . "</a>";

					if($Record['SpouseID'] != null)
					{
						$SpouseID = Database::Filter($Record['SpouseID']);

						$SQL = "SELECT RecordID, FirstName, LastName
								FROM Records
								WHERE RecordID=$SpouseID
								";

						$Data2 = Database::Select($SQL);

						if(count($Data2) == 1)
						{
							echo "<a href=\"" . GetPageURL('database/view/' . $Data2[0]['RecordID']) . "\">";
					echo $Data2[0]['FirstName'] . "</a>";
						}
					}

					
					if($Record['MotherID'] != null || $Record['FatherID'] != null)
					{
						self::DisplayAllChildren($Record['RecordID']);
						// recursively call function
					}

					echo "</li>";
					
				}
				echo "</ul>";
			}
		}

	}

	public static function FindOldestRelative($RecordID)
	{

		if(!is_pos_int($RecordID))
		{
			Server::ErrorMessage("Record ID must be a positive integer");
		}
		else
		{
			$RecordID = Database::Filter($RecordID);
			// prevent injection

			$SQL = "SELECT MotherID, FatherID
					FROM Records
					WHERE RecordID=$RecordID";
			// get mother and father of $RecordID

			$Data = Database::Select($SQL);
			// query database

			if(count($Data) == 1)
			// if record has been found.
			{
				$FatherID = $Data[0]['FatherID'];
				// get father ID of record

				$MotherID = $Data[0]['FatherID'];
				// get mother ID of record

				if($FatherID != null)
				{
					$RecordID = self::FindOldestRelative($FatherID);		
				}

				if($MotherID != null)
				{
					$RecordID = self::FindOldestRelative($MotherID);		
				}

				return $RecordID;

			}
			else if(count($Data) > 1)
			{
				Server::ErrorMessage("Error: Multiple records with (PRIMARY KEY: RecordID)");
			}
			else
			{
				Server::ErrorMessage("Record not found.");
			}

		}
	}
}

Class Bootstrap
{
	static public function DisplayBreadcrumb()
	{
		$URL = GetCurrentPath();
		$Path = $URL['call_parts'];

		echo "<ol class=\"breadcrumb\">";

		echo "<li><a href=\"#\">Main Site</a></li>"; 

		foreach($Path as $Part)
		{
			echo "<li><a href=\"#\">" . ucwords($Part) . "</a></li>";
		}

		echo "</ol>";

	}
}