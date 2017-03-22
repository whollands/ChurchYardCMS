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

class Database {

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
	//private $Config = include("config/general.php");

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
}

Class Jobs
{
	function GenerateSitemap()
	{

	}

	function GenerateRobots()
	// creates a robots.txt file
	{
		$Data = "Disallow: application/";
		$Data .= "\nDisallow: config/";
		$Data .= "\nDisallow: templates/";
	}

	function ClearCache()
	{
		foreach (new DirectoryIterator('cache/') as $fileInfo) 
		{
		    if(!$fileInfo->isDot())
		    {
		        unlink($fileInfo->getPathname());
		    }
		}
	}

	function IndexSearch()
	{

	}
}

Class User
{
	private $UserTokenCookie = "UserToken";
	// id of cookie that stores user's session

	public static $UserID = null;
	public static $Username = null;
	public static $Name = "Guest";
	public static $EmailAddress = null;
	public static $IsLoggedin = false;
	public static $IsAdmin = false;


    function __construct()
	{
		
		// Create connection

		$SessionToken = Database::Filter($_COOKIE["SessionToken"]);

		$Data = Database::Select("SELECT UserID FROM Sessions WHERE SessionToken=$SessionToken");
		// Execute

		if(count($Data) == 1)
		{
			$this->IsLoggedin = true;

			$UserID = Database::Filter($Data[0]['UserID']);
			$SQL = "SELECT UserID, Username, Name, EmailAddress, IsAdmin FROM Users WHERE UserID=$UserID";
			$Data = Database::Select($SQL)or Server::ErrorMessage(Database::Error());

			self::$UserID = $Data[0]['UserID'];
			self::$Username = $Data[0]['Username'];
			self::$Name = $Data[0]['Name'];
			self::$EmailAddress = $Data[0]['EmailAddress'];
			self::$IsAdmin = $Data[0]['IsAdmin'];
		}

		
	}

	private function GetUserSalt($Username)
	{
		
		// Create connection

		if(!preg_match("/^[\w.]*$/", $Username))
		{
			Server::ErrorMessage("Username can only contain alphanumeric, periods and underscores");
		}

		$Username = Database::Filter($Username);
		// Prevent injection

		$Data = Database::Select("SELECT Salt FROM Users WHERE Username=$Username");
		// Execute

		return $Data[0]['Salt'];
	}

	public function CheckUserExists($UserID)
	{
		if(!preg_match("/^[\d]*$/", $UserID))
		{
			Server::ErrorMessage("User ID must be a positive integer");
		}

		
		$UserID = Database::Filter($UserID);
		$SQL = "SELECT UserID FROM Users WHERE UserID=$UserID";
		$Data = Database::Select($SQL);

		if(count($Data) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function GetUserID()
	{
		return self::$UserID;
	}

	public function CheckCredentials($Username, $Password)
	{

		$UserSalt = $this->GetUserSalt($Username);
		// use object function to retrive user salt

		$MasterSalt = $GLOBALS["Config"]->MasterSalt;
		// get master salt from configuration

		$Password =  md5($MasterSalt . $Password . $UserSalt);
		// hash password

		
		// Create connection

		$Username = Database::Filter($Username);
		$Password = Database::Filter($Password);
		// Prevent injection on input

		$SQL = "SELECT UserID, Name, EmailAddress, IsAdmin, Username FROM Users WHERE Username=$Username AND Password=$Password";
		// prepare

		$Data = Database::Select($SQL);
		// Execute
		
		if(Count($Data) == 1)
	    // if user is found in database
		{

			$RandomToken = GetRandomToken();
			// create a new random session token

			$UserID = Database::Filter($Data[0]['UserID']);
			$this->UserID = $UserID;
			// We know 1 record was found, so $Data[0] refers to the first record in the array
			// Prevent injection

			$this->Name = $Data[0]['Name'];
			$this->EmailAddress = $Data[0]['EmailAddress'];
			$this->Name = $Data[0]['Name'];

			$this->IsLoggedin = true;

			$SessionToken = Database::Filter($RandomToken);
			$IPAddress = Database::Filter($_SERVER['REMOTE_ADDR']);
			// Prevent injection

			$SQL = "INSERT INTO Sessions (UserID, SessionToken, IP) VALUES ($UserID, $SessionToken, $IPAddress)";
			// prepare satement

			Database::Query($SQL)or Server::ErrorMessage(Database::Error());
			// insert session into database
			// or die with error message

			setcookie("SessionToken", $RandomToken, time() + (3600 * 24 * 30), "/");
			// save session token to user's computer

			return true;
			// user is authenticated
		}
		else
		{
			return false;
			// false means username and/or password incorrect
		}

		
		// destroy database object
	}

	public function ChangePassword($UserID, $NewPassword)
	{
		$UserSalt = GetRandomToken();
		// generate a new random user salt

		$NewPassword = $GLOBALS["Config"]->MasterSalt . md5($NewPassword) . $UserSalt;
		// create a hash for the password

		$NewPassword = Database::Filter($NewPassword);
		$UserSalt = Database::Filter($UserSalt);
		$UserID = Database::Filter($UserID);
		// prevent injection

		$SQL = "UPDATE Users SET Password=$NewPassword AND Salt=$UserSalt WHERE UserID=$UserID";
		// prepare query

		
		// open connection
		
		if(!Database::Query($SQL)or Server::ErrorMessage(Database::Error()))
		// perform query to update database
		{
			Server::ErrorMessage("Failed to update new password to database.");
		}
		else
		{
			Server::ErrorMessage("Success.");
		}
		
	}

	public function CheckAuthenticated()
	{
		if($this->IsLoggedin == false)
		{
			Server::Redirect("login");
		}
	}

	public function Logout()
	{
		

		$SessionToken = Database::Filter($_COOKIE["SessionToken"]);

		$Data = Database::Query("DELETE FROM Sessions WHERE SessionToken=$SessionToken")or Server::ErrorMessage(Database::Error());
		// Execute

		setcookie("SessionToken", "", time() -3600);
	}

	public function DeleteSession($SessionID)
	{
		if(!preg_match("/^[0-9]*$/", $SessionID))
		{
			Server::ErrorMessage("Session ID must be an integer");
		}
		else
		{
			
			$SessionID = Database::Filter($SessionID);
			$SQL = "DELETE FROM Sessions WHERE SessionID=$SessionID";
			Database::Query($SQL)or Server::ErrorMessage(Database::Error());
			
		}
	}

	public function Update()
	{

	}

	public function Delete($UserID)
	{
		if(!preg_match("/^[0-9]*$/", $UserID))
		{
			Server::ErrorMessage("User ID must be an integer");
		}
		else if(!$this->CheckUserExists($UserID))
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
	//private $CleanURLs = $GLOBALS['Config']->URL->CleanURLs;

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
		if(!preg_match("/^[0-9]*$/", $PageID))
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

	public function Upload()
	{

	}	

	public function Delete()
	{

	}

	public function Modify()
	{

	}
}

Class Grave
{

}

Class Record
{

	public function GetRecord($RecordID)
	{
		if(!preg_match("/^[0-9]*$/", $RecordID))
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
			
			echo "<strong>FirstName: </strong>" . $Data[0]['FirstName'] . "
		    <br><strong>LastName: </strong>" . $Data[0]['LastName'] . "
		    <br><strong>Date Of Birth: </strong>" . ConvertDate($Data[0]['DateOfBirth']) . "

		  <br><strong>Date Of Death: </strong>" . ConvertDate($Data[0]['DateOfDeath']);

		  
		}
	}

	public function CheckRecordExists($RecordID)
	{
		if(!preg_match("/^[\d]*$/", $RecordID))
		{
			Server::ErrorMessage("Record ID must be a positive integer");
		}

		
		$RecordID = Database::Filter($RecordID);
		$SQL = "SELECT RecordID FROM Records WHERE RecordID=$RecordID";
		$Data = Database::Select($SQL);
		
		if(count($Data) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function Create()
	{

	}

	

}

Class Map
{
	public static function GetGraveList()
	{
		
		$Data = Database::Select("SELECT GraveID, XCoord, YCoord FROM Graves ORDER BY YCoord ASC, XCoord ASC");
		
		$len = count($Data);

		foreach($Data as $i)
		{

			echo $i['XCoord'] . ":" . $i['YCoord'] . ",";
		}

	}
}