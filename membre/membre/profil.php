<?php session_start();
include('header.php');
include('../photo.php');
/*if(!empty($_POST['changeDate_Naissance'])) {
	Membre::profilVisibilite($_SESSION['id'], 'date_naissance');
}
if(!empty($_POST['changeCivilite'])) {
	Membre::profilVisibilite($_SESSION['id'], 'civilite');
}
if(!empty($_POST['changeNom'])) {
	Membre::profilVisibilite($_SESSION['id'], 'nom');
}
if(!empty($_POST['changePrenom'])) {
	Membre::profilVisibilite($_SESSION['id'], 'prenom');
}
if(!empty($_POST['changeMail'])) {
	Membre::profilVisibilite($_SESSION['id'], 'mail');
}
if(!empty($_POST['changePays'])) {
	Membre::profilVisibilite($_SESSION['id'], 'pays');
}

if(!empty($_POST['changeVille'])) {
	Membre::profilVisibilite($_SESSION['id'], 'ville');
}*/
if(!empty($_POST['maj'])) {
	extract($_POST);
	if(empty($date_naissance) || $date_naissance>date('Y-m-d')) $date_naissance = NULL;
	if(($_FILES['photo']['error'])==0 )  {
		if(($_FILES['photo']['size'])<102400) { //100ko
			if(!( ($_FILES['photo']['type']!= "image/gif") && ($_FILES['photo']['type']!= "image/jpeg") && ($_FILES['photo']['type']!= "image/png"))){
				$uploaddir = $folder.'/Images/membre/';
				if(testPhoto($uploaddir)){
					$photo = $_FILES['photo']['name'];
				}
				else {
					$err = 'Erreur lors de l\'importation du fichier. Veuillez réessayer avec une autre photo.';
				}
			}
			else {
				$err = 'Le format de la photo est incorrect. Formats autorisés: .jpg/.jpeg, .gif, .png.';
			}
		}
		else {
			$err = 'Le fichier est trop volumineux. Maximum: 100 ko.';
		}
	}
	elseif (!empty($_Files['photo']['name'])) {
		$err = 'Veuillez entrer une photo valide.';
	}
	else{
		$photo="";
	}
	if(Message::interdit($description)) {
		if(isset($nom) && !empty($nom) && isset($prenom) && !empty($prenom) && isset($mail) && !empty($mail) && isset($voie) && !empty(($voie))) {
			if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				Membre::majProfil($_SESSION['id'], $date_naissance, $civilite, $nom, $prenom, $mail, $pays, $ville,$voie,$profession,$nom_entreprise, 0, $description,$photo);
			}
			else {
				$err = 'Votre adresse email n\'est pas conforme.';
			}
		}
		else {
			$err = 'Tous les champs marqués d\'une astérisque doivent être complétés.';
		}
	}
	else {
		$err = 'Votre description contient du language sms ou des mots interdits.';
	}
}

if(!empty($err)) {
	alertr($err);
}

include('menu.php');
echo '
<div>Votre Profil '.Membre::info($_SESSION['id'], 'pseudo').'</div>
<form action="" method="post" enctype="multipart/form-data">
<table width="70%" align="center">';

echo  
//<tr>
//<td width="140px" rowspan="14" valign="top">
//<input type="hidden" name="id_avatar" value="'.Membre::info($_SESSION['id'], 'id_avatar').'">
//<img src="'.URLSITE.'/'.Avatar::membre(Membre::info($_SESSION['id'], 'id_avatar')).'" width="120" height="120" alt="Avatar" title="Avatar"><br />
//<a href="avatar.php" class="input">Changer d\'Avatar</a> 
//</td>
//</tr>

'<style> .put{width:150px;} </style>
<tr>
<td align="right" class="titre_form">Changez votre photo :
<input type="hidden" name="MAX_FILE_SIZE" value="2048576" /></td>
<td>
<input type="file" name="photo" value="'.Membre::info($_SESSION['id'], 'photo').'">
</td>
<td>
<img class="img_profil" src="'.$folder.'Images/membre/'.Membre::info($_SESSION['id'], 'photo').'" alt="photo">
</td>
</tr>
<tr>
<td align="right" class="titre_form">Votre Mail* : </td>
<td>
<input class="put" type="text" name="mail" value="'.Membre::info($_SESSION['id'], 'mail').'">
</td></tr>
<tr>
<td align="right" class="titre_form">Votre Nom* : </td>
<td>
<input class="put" type="text" name="nom" value="'.Membre::info($_SESSION['id'], 'nom').'">
</td>
</tr><tr>
<td align="right" class="titre_form">Votre Pr&eacute;nom* :</td>
<td> 
<input class="put" type="text" name="prenom" value="'.Membre::info($_SESSION['id'], 'prenom').'">
</td>
</tr><tr>
<td align="right" class="titre_form">Civilité : </td>
<td>
<input name="civilite" type="radio" value="h" ';
if(Membre::info($_SESSION['id'], 'civilite')=='h') { 
	echo 'checked'; 
}
echo '>Homme <input name="civilite" type="radio" value="f" ';
if(Membre::info($_SESSION['id'], 'civilite')=='f') { 
	echo 'checked'; 
}
echo '>Femme
</td>
</tr><tr>
<td align="right" class="titre_form">Date de Naissance* : </td>
<td>
<input class="put" type="date" name="date_naissance" value="'.Membre::info($_SESSION['id'], 'date_naissance').'" placeholder="AAAA-MM-JJ">
</td>
</tr>
<tr>
<td align="right" class="titre_form">Votre Pays : </td>
<td>
<select class="put" name="pays" id="pays">'; include("../listepays.php"); echo '</select>
</td>
</tr><tr>
<td align="right" class="titre_form">Votre Ville : </td>
<td>
<input class="put" type="text" name="ville" value="'.Membre::info($_SESSION['id'], 'ville').'">
</td>
</tr><tr>
<td align="right" class="titre_form">Adresse : </td>
<td>
<input class="put" type="text" name="voie" value="'.Membre::info($_SESSION['id'], 'voie').'">
</td>
</tr>
';
if (Membre::info($_SESSION['id'], 'type')=="pro")
{
	echo '<tr>
<td align="right" class="titre_form">Profession : </td>
<td>
<input class="put" type="text" name="profession" value="'.Membre::info($_SESSION['id'], 'profession').'">
</td>
</tr><tr>
<td align="right" class="titre_form">Entreprise : </td>
<td>
<input class="put" type="text" name="nom_entreprise" value="'.Membre::info($_SESSION['id'], 'nom_entreprise').'">
</td>
</tr>';
}
echo
'<tr>
<td align="right" colspan="4">
<a href="change_pass.php" class="input">Changer le mot de passe</a>
</td>
</tr><tr>
<td colspan="3"><p>&nbsp;</p></td>
</tr><tr>
<td colspan="3" align="center" class="titre_form">Votre Description :</td>
</tr><tr>
<td colspan="3" align="center">
<textarea name="description" cols="80" rows="5">'.str_replace('<br />', "\n",Membre::info($_SESSION['id'], 'description')).'</textarea>
</td>
</tr><tr>
<td colspan="3" align="center">
<br />
<input type="submit" value="Mettre à jour le Profil" name="maj" class="input">
<br /><br />
</td>
</tr>
</table>
</form>
';
include('footer.php');
?>
