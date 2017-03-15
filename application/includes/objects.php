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

    // The database Connection
    protected static $Conn;
   
    public function Connect() 
    {    
        // Try and connect to the database
        if(!isset(self::$Conn))
        {   
        	$Config = include("config/database.php");
        	// Load config from database file
        	// encapsulated within Connect() function
     
        	self::$Conn = new mysqli($Config->Host, $Config->Username, $Config->Password, $Config->DatabaseName);
        }

        // If conn was NOT successful, handle the error
        if(self::$Conn === false)
        {
            die("Database error");
            return false;
        }
        return self::$Conn;
    }

    public function Disconnect()
    {
    	self::$Conn -> close();
    	// use the default MySQL close() function
    }

    public function Query($query)
    {
        // Connect to the database
        $Conn = $this -> connect();

        // Query the database
        $Result = $Conn -> query($query);

        return $Result;
    }

    public function Select($query)
    {
        $Data = array();

        $Result = $this -> query($query);

        if($Result === false)
        {
            return false;
        }

        while ($Row = $Result -> fetch_assoc())
        {
            $Data[] = $Row;
        }
        // return the result of the query as an array.

        return $Data;
    }

    public function Error() 
    {
    	if($GLOBALS['Config'] -> Dev -> EnableDebug)
    	{
    		$Conn = $this -> connect();
        	return "<h2>Database Error:</h2> <p>" . $Conn -> error . "</p>";
    	}
    	else
    	{
    		return "An error occurred whilst connecting to the database.";
    	}
        
    }

    public function Filter($value)
    {
        $Conn = $this -> connect();
        return "'" . $Conn -> real_escape_string($value) . "'";
    }
}

Class User
{
	private $UserTokenCookie = "UserToken";
	// id of cookie that stores user's session

	public $UserID = null;
	public $Username = null;
	public $EmailAddress = null;
	public $IsAdmin = false;

	function GetUserSalt($Username)
	{
		$Db = new Database();
		// Create connection

		$Username = $Db -> Filter($Username);
		// Prevent injection

		$Data = $Db -> Select("SELECT Salt FROM Users WHERE Username=$Username");
		// Execute

		return $Data[0]['Salt'];
	}

	function GetUserID()
	{
		$Db = new Database();
		// Create connection

		$SessionToken = $_COOKIE["SessionToken"];

		$SQL = "SELECT Users.UserID FROM Users, Sessions WHERE Sessions.SessionID = $SessionToken AND Sessions.UserID = Users.UserID";

		$Data = $Db -> Select($SQL)or die($Db -> Error());

		echo $Data[0]['UserID'];

		die();
	}

	function CheckCredentials($Username, $Password)
	{

		$UserSalt = $this -> GetUserSalt($Username);

		$MasterSalt = $GLOBALS["Config"]->MasterSalt;

		$Password =  md5($MasterSalt . $Password . $UserSalt);

		$Db = new Database();
		// Create connection

		$Username = $Db -> Filter($Username);
		$Password = $Db -> Filter($Password);
		// Prevent injection on input

		$SQL = "SELECT UserID, Name, Username FROM Users WHERE Username=$Username AND Password=$Password";
		// prepare

		$Data = $Db -> Select($SQL);
		// Execute
		
		if(Count($Data) == 1)
		{

			

			$RandomToken = GetRandomToken();

			$UserID = $Db -> Filter($Data[0]['UserID']);
			$SessionToken = $Db -> Filter($RandomToken);
			$IPAddress = $Db -> Filter($_SERVER['REMOTE_ADDR']);
			// Prevent injection

			$SQL = "INSERT INTO Sessions (UserID, SessionToken, IP) VALUES ($UserID, $SessionToken, $IPAddress)";
			// prepare

			$Db -> Query($SQL)or die($Db -> Error());
			// insert session into database

			setcookie("SessionToken", $RandomToken, time() + (3600 * 24 * 30), "/");
			// save session token to user's computer

			return true;
		}
		else
		{
			return false;
		}

		unset($Db);
	}

	function ChangePassword($UserID, $NewPassword)
	{
		$UserSalt = GetRandomToken();
		// generate a new random user salt

		$NewPassword = $GLOBALS["Config"]->MasterSalt . md5($NewPassword) . $UserSalt;
		// create a hash for the password

		$NewPassword = $Db -> Filter($NewPassword);
		$UserSalt = $Db -> Filter($UserSalt);
		$UserID = $Db -> Filter($UserID);
		// prevent injection

		$SQL = "UPDATE Users SET Password=$NewPassword AND Salt=$UserSalt WHERE UserID=$UserID";
		// prepare query

		$Db = new Database();
		// open connection
		
		if(!$Db -> Query($SQL)or die($Db -> Error()))
		// perform query to update database
		{
			die("Failed to update new password to database.");
		}
		else
		{
			die("Success.");
		}
		
	}

	function IsLoggedin()
	{
		$Db = new Database();
		// Create connection

		$SessionToken = $Db -> Filter($_COOKIE["SessionToken"]);

		$Data = $Db -> Select("SELECT UserID FROM Sessions WHERE SessionToken=$SessionToken");
		// Execute

		if(count($Data) != 1)
		{
			header("Location: " . GetPageURL("login"));
			exit;
		}
	}

	function Logout()
	{
		$Db = new Database();

		$SessionToken = $Db -> Filter($_COOKIE["SessionToken"]);

		$Data = $Db -> Query("DELETE FROM Sessions WHERE SessionToken=$SessionToken")or die($Db -> Error());
		// Execute

		$Db -> Disconnect();

		setcookie("SessionToken", "", time() -3600);
		session_destroy();
	}
}

Class Page
{
	// private $Base = $GLOBALS['Config']->URL->Base;
	//private $CleanURLs = $GLOBALS['Config']->URL->CleanURLs;

	function DisplayContent()
	{
		$Db = new Database();

		$Path = GetCurrentPath();

		$PageURL = implode("/", $Path["call_parts"]);

		if($PageURL == null)
		{
		    $PageURL = "homepage";
		    // load homepage if no URL specified
		}

		$PageURL = $Db -> Filter($PageURL);

		$Data = $Db -> Select("SELECT PageName, Content FROM Pages WHERE URL=$PageURL");


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

	function DisplayNavigation()
	{

	}

}

Class Server
{
	function GetFullPath()
	{

	}

	function GetPageURL()
	{
		$URL = $Base . $File;

		if($CleanURLs)
		{
			$URL = $Base . "/index.php" . $File;
		}

		return $URL;
	}

	function GetResourceURL($File = "")
	{
		return $Base . $File;
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

Class Media
{
	function GetURLByID()
	{

	}

	function Upload()
	{

	}	

	function Delete()
	{

	}

	function Modify()
	{

	}
}

Class Map
{
	function GetGraveList()
	{
		$Db = new Database();
		$Data = $Db -> Select("SELECT GraveID, XCoord, YCoord FROM Graves ORDER BY YCoord ASC, XCoord ASC");
		
		return serialize($Data);
		// for($i = 0; $i = $Data['XCoord'][0]; i++)
		// {
		// 	for($i = 0; $i = $Data['XCoord'][0]; i++)
		// 	{
		// 	}
		// }
	}
}