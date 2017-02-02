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
    // The database conn
    protected static $conn;

   
    public function Connect() 
    {    
        // Try and connect to the database
        if(!isset(self::$conn))
        {   
        	$Config = include("config/database.php");
        	// Load config from database file

            self::$conn = new mysqli($Config->Host, $Config->Username, $Config->Password, $Config->DatabaseName);
        }

        // If conn was not successful, handle the error
        if(self::$conn === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$conn;
    }

    public function Query($query)
    {
        // Connect to the database
        $conn = $this -> connect();

        // Query the database
        $result = $conn -> query($query);

        return $result;
    }

    public function Select($query)
    {
        $rows = array();
        $result = $this -> query($query);
        if($result === false) {
            return false;
        }
        while ($row = $result -> fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function Error() 
    {
        $conn = $this -> connect();
        return $conn -> error;
    }

    public function Filter($value)
    {
        $conn = $this -> connect();
        return "'" . $conn -> real_escape_string($value) . "'";
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

	function CheckCredentials($Username, $Password)
	{

		$UserSalt = $this -> GetUserSalt($Username);

		$MasterSalt = $GLOBALS["Config"]->MasterSalt;

		$Password =  md5($MasterSalt . $Password . $UserSalt);


		$db = new Database();
		// Create connection

		$Username = $db -> Filter($Username);
		$Password = $db -> Filter($Password);
		// Prevent injection

		$Data = $db -> Select("SELECT UserID, Name, Username FROM Users WHERE Username=$Username AND Password=$Password");
		// Execute
		
		if(Count($Data) == 1)
		{

			$RandomToken = GetRandomToken();

			$UserID = $db -> Filter($Data[0]['UserID']);
			$SessionToken = $db -> Filter($RandomToken);
			// Prevent injection

			$db = new Database();
			// Create connection

			$db -> Query("INSERT INTO Sessions (UserID, SessionToken) VALUES ($UserID, $SessionToken)");
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

	function ChangePassword($Username, $NewPassword)
	{
		$UserSalt = GetRandomToken();
		// generate a new random user salt

		$NewPassword = $GLOBALS["Config"]->MasterSalt . md5($NewPassword) . $UserSalt;
		// create a hash for the password

		$Database = new Database();
		$Database->Update("UPDATE Users SET Password = $NewPassword AND Salt = $UserSalt WHERE Username = $Username");
		// perform query to update database
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
		// Create connection

		$SessionToken = $db -> Filter($_COOKIE["SessionToken"]);

		$Data = $db -> Query("DELETE FROM Sessions WHERE SessionToken=$SessionToken");
		// Execute

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

Class RecordSearch
{

}

Class Cache
{
	function CachePage()
	{

	}

	function CacheFamilyData()
	{

	}

	function CacheMap()
	{

	}
}

Class Map
{
	function GetGraveData()
	{

	}

}
