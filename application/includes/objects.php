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
        	return "<h2>Database Error:</h2> <p>" .$Conn -> error . "</p>";
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

	function GetUserSalt($Username)
	{
		$db = new Database();
		// Create connection

		$Username = $db -> Filter($Username);
		// Prevent injection

		$Data = $db -> Select("SELECT Salt FROM Users WHERE Username=$Username");
		// Execute

		return $Data[0]['Salt'];
	}

	function GetUserID()
	{
		$Db = new Database();
		// Create connection

		$SQL = "SELECT UserID FROM Sessions, Users WHERE Sessions.SessionID = $SessionToken AND Sessions.UserID = Users.UserID";

		$Data = $Db -> Select($SQL)or die($Db -> Error());

		echo "";

		die();
	}

	function CheckCredentials($Username, $Password)
	{

		$UserSalt = $this -> GetUserSalt($Username);

		$MasterSalt = $GLOBALS["Config"]->MasterSalt;

		$Password =  md5($MasterSalt . $Password . $UserSalt);

		$db = new Database();
		// Create connection

		$Username = $db -> Filter($Username);
		$Password = $db -> Filter($Password);
		// Prevent injection on input

		$SQL = "SELECT UserID, Name, Username FROM Users WHERE Username=$Username AND Password=$Password";
		// prepare

		$Data = $db -> Select($SQL)or die($db -> Error());
		// Execute
		
		if(Count($Data) == 1)
		{

			$RandomToken = GetRandomToken();

			$UserID = $db -> Filter($Data[0]['UserID']);
			$SessionToken = $db -> Filter($RandomToken);
			$IPAddress = $db -> Filter($_SERVER['REMOTE_ADDR']);
			// Prevent injection

			$db = new Database();
			// Create connection

			$SQL = "INSERT INTO Sessions (UserID, SessionToken, IP) VALUES ($UserID, $SessionToken, $IPAddress)";
			// prepare

			$db -> Query($SQL)or die($db -> Error());
			// insert session into database

			setcookie("SessionToken", $RandomToken, time() + (3600 * 24 * 30), "/");
			// save session token to user's computer

			return true;
		}
		else
		{
			return false;
		}
	}

	function ChangePassword($UserID, $NewPassword)
	{
		$UserSalt = GetRandomToken();
		// generate a new random user salt

		$NewPassword = $GLOBALS["Config"]->MasterSalt . md5($NewPassword) . $UserSalt;
		// create a hash for the password

		$NewPassword = $db -> Filter($NewPassword);
		$UserSalt = $db -> Filter($UserSalt);
		$UserID = $db -> Filter($UserID);
		// prevent injection

		$SQL = "UPDATE Users SET Password=$NewPassword AND Salt=$UserSalt WHERE UserID=$UserID";
		// prepare query

		$db = new Database();
		// open connection
		
		if(!$db -> Query($SQL)or die($db -> Error()))
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
		$db = new Database();
		// Create connection

		$SessionToken = $db -> Filter($_COOKIE["SessionToken"]);

		$Data = $db -> Select("SELECT UserID FROM Sessions WHERE SessionToken=$SessionToken");
		// Execute

		if(count($Data) != 1)
		{
			header("Location: " . GetPageURL("login"));
			exit;
		}
	}

	function Logout()
	{
		$db = new Database();

		$SessionToken = $db -> Filter($_COOKIE["SessionToken"]);

		$Data = $db -> Query("DELETE FROM Sessions WHERE SessionToken=$SessionToken")or die($db -> Error());
		// Execute

		$db -> Disconnect();

		setcookie("SessionToken", "", time() -3600);
		session_destroy();
	}
}

Class WebAddress
{
	// private $Base = $GLOBALS['Config']->URL->Base;
	//private $CleanURLs = $GLOBALS['Config']->URL->CleanURLs;

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