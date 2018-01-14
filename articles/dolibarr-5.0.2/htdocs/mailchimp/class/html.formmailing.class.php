<?php
/* Copyright (C) 2014 Florian Henry florian.henry@open-concept.pro
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/**
 *	\file       htdocs/core/class/html.formmailing.class.php
 *  \ingroup    core
 *	\brief      File of predefined functions for HTML forms for mailing module
 */
require_once DOL_DOCUMENT_ROOT .'/core/class/html.form.class.php';

/**
 *	Class to offer components to list and upload files
 */
class FormMailing  extends Form
{
	public $db;
	public $error;
	public $errors=array();

	
	/**
	 *	Constructor
	 *
	 *  @param		DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
		parent::__construct($db);
		return 1;
	}

	public function select_destinaries_status($selectedid='',$htmlname='dest_status', $show_empty=0) {
		
		global $langs;
		$langs->load("mails");
		
		require_once DOL_DOCUMENT_ROOT.'/comm/mailing/class/mailing.class.php';
		$mailing = new Mailing($this->db);
		
		
		$array = $mailing->statut_dest; 
		//Cannot use form->selectarray because empty value is defaulted to -1 in this method and we use here status -1...
		
		$out = '<select name="'.$htmlname.'" class="flat">';
		
		if ($show_empty) {
			$out .= '<option value=""></option>';
		}
		
		foreach($mailing->statut_dest as $id=>$status) {
			if ($selectedid==$id)  {
				$selected=" selected=selected ";
			}else {
				$selected="";
			}
			$out .= '<option '.$selected.' value="'.$id.'">'.$langs->trans($status).'</option>';
		}
		
		$out .= '</select>';
		return $out;
	}
}