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


function GetCurrentPath() {
  $Path = array();

  if (isset($_SERVER['REQUEST_URI'])) 
  {
    $request_path = explode('?', $_SERVER['REQUEST_URI']);
    $Path['base'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
    $Path['call_utf8'] = substr(urldecode($request_path[0]), strlen($Path['base']) + 1);
    $Path['call'] = utf8_decode($Path['call_utf8']);

    if ($Path['call'] == basename($_SERVER['PHP_SELF']))
    {
      $Path['call'] = '';
    }

    $Path['call_parts'] = explode('/', $Path['call']);
  
  
  }
return $Path;
}


function GetPageURL($file = "")
{
	if($GLOBALS['Config']->URL->CleanURLs == true)
	{
		return $GLOBALS['Config']->URL->Base . $file;
        // if clean URLs are enabled, then no need to
        // display /index.php in the URL
	}
	else
	{
		return $GLOBALS['Config']->URL->Base . "index.php/" . $file;
        // if clean URL's have not been enabled in .htaccess then
        // /index.php/ will be required in the URL
	}

	return $GLOBALS["Config"]->URL->Base;
}

function GetResourceURL($URL = "")
{
	return $GLOBALS['Config']->URL->Base . $URL;
    // get the full uri for a file parsed
}

function GetRandomToken()
{
    return md5(uniqid(rand(), true));
    // return random md5 hash
}

function IncludeScript($URI)
{
    $URI = "application/pages/" . $URI;

    if(!file_exists($URI))
    {
        Server::ErrorMessage("Failed to locate script " . $URI);
    }
    include($URI);
}

function ConvertDate($Date)
{
    $Date = strtotime($Date);
    $Date = date('n M Y', $Date);
    return $Date;
}

/* --------------------------------------------

    Pre-Configured Bootstrap
    Alert Messages

    http://getbootstrap.com/components/#alerts

----------------------------------------------- */


function AlertSuccess($Message)
{
    $HTML = "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">";
    $HTML .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
    $HTML .= "<i class=\"fa fa-check\"></i>&nbsp;&nbsp;&nbsp;" . $Message . "</div>";
    return $HTML;
}
function AlertWarning($Message)
{
    $HTML = "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\">";
    $HTML .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
    $HTML .= "<i class=\"fa fa-warning\"></i>&nbsp;&nbsp;&nbsp;" . $Message . "</div>";
    return $HTML;
}
function AlertDanger($Message)
{
    $HTML = "<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">";
    $HTML .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
    $HTML .= "<i class=\"fa fa-times\"></i>&nbsp;&nbsp;&nbsp;" . $Message . "</div>";
    return $HTML;
}
function AlertInfo($Message)
{
    $HTML = "<div class=\"alert alert-info alert-dismissible\" role=\"alert\">";
    $HTML .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
    $HTML .= "<i class=\"fa fa-info\"></i>&nbsp;&nbsp;&nbsp;" . $Message . "</div>";
    return $HTML;
}

function Button ($Title, $URL, $CSS = "btn btn-default")
{
    $HTML = "<a class=\"$CSS\" href=\"$URL\" role=\"button\">$Title</a>";
    return $HTML;
}
