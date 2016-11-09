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

/* --------------------------------------------

    Pre-Configured Bootstrap
    Alert Messages

    http://getbootstrap.com/components/#alerts

----------------------------------------------- */

function AlertSuccess($message)
{
	$html = "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">";
	$html .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
	$html .= "<i class=\"fa fa-check\"></i>&nbsp;&nbsp;&nbsp;" . $message . "</div>";
	return $html;
}
function AlertWarning($message)
{
	$html = "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\">";
	$html .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
	$html .= "<i class=\"fa fa-check\"></i>&nbsp;&nbsp;&nbsp;" . $message . "</div>";
	return $html;
}
function AlertDanger($message)
{
	$html = "<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">";
	$html .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
	$html .= "<i class=\"fa fa-times\"></i>&nbsp;&nbsp;&nbsp;" . $message . "</div>";
	return $html;
}
function AlertInfo($message)
{
	$html = "<div class=\"alert alert-info alert-dismissible\" role=\"alert\">";
	$html .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
	$html .= "<i class=\"fa fa-info\"></i>&nbsp;&nbsp;&nbsp;" . $message . "</div>";
	return $html;
}