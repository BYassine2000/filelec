<?php

if (isset($_SESSION['idclient'])) {
    header('Location: /filelec/');
    exit();
}

$unControleur->setTable("question");
$lesQuestions = $unControleur->selectAll("*");

if (isset($_POST['InscriptionParticulier'])) {
	$unControleur->setTable("particulier");
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	$tel = $_POST['tel'];
	$email = $_POST['email'];
	$mdp = $_POST['mdp'];
	$mdp2 = $_POST['mdp2'];
	$adresse = $_POST['adresse'];
	$cp = $_POST['cp'];
	$ville = $_POST['ville'];
	$pays = $_POST['pays'];
	$enonce = $_POST['enonce'];
	$reponse = $_POST['reponse'];
	if ($nom != "") {
		if (preg_match("#^[A-Z][a-zA-Z]{1,50}$#", $nom)) {
			if ($prenom != "") {
				if (preg_match("#^[A-Z][a-zA-Z]{1,50}$#", $prenom)) {
					$telLength = strlen($tel);
					if ($telLength == 10) {
						$unControleur->setTable("client");
						$where = array("tel"=>$tel);
						$checkTelClient = $unControleur->selectWhere("tel", $where);
						if (!$checkTelClient) {
							$unControleur->setTable("particulier");
							$where1 = array("tel"=>$tel);
							$checkTelParticulier = $unControleur->selectWhere("tel", $where1);
							if (!$checkTelParticulier) {
								if ($email != "") {
									if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
										if (preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,6}$#", $_POST['email'])) {
											$unControleur->setTable("client");
											$where2 = array("email"=>$email);
											$checkEmailClient = $unControleur->selectWhere("email", $where2);
											if (!$checkEmailClient) {
												$unControleur->setTable("particulier");
												$where3 = array("email"=>$email);
												$checkEmailParticulier = $unControleur->selectWhere("email", $where3);
												if (!$checkEmailParticulier) {
													if ($mdp != "") {
														if (preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!&?;%:#+=<>*._-])[A-Za-z\d@$!&?;%:#+=<>*._-]{8,}$/", $mdp)) {
															if ($mdp == $mdp2) {
																if ($adresse != "") {
																	if ($cp != "") {
																		if (preg_match("#^[0-9]{5}|2[A-B][0-9]{3}$#", $cp)) {
																			if ($ville != "") {
																				if ($pays != "") {
																					if (preg_match("#^[A-Z][a-zA-Z-]{1,50}$#", $pays)) {
																						$tab1 = array(
																							"nom"=>$nom,
																							"prenom"=>$prenom,
																							"tel"=>$tel,
																							"email"=>$email,
																							"mdp"=>$mdp,
																							"adresse"=>$adresse,
																							"cp"=>$cp,
																							"ville"=>$ville,
																							"pays"=>$pays,
																							"etat"=>'Prospect',
																							"role"=>'client'
																						);
																						$unControleur->appelProc("insertParticulier", $tab1);
																						if ($reponse != "") {
																							$unControleur->setTable("reponse");
																							$tab2 = array(
																								"enonce"=>$enonce,
																								"reponse"=>$reponse,
																								"email"=>$email,
																								"mdp"=>$mdp
																							);
																							$unControleur->appelProc("insertReponse", $tab2);
																							echo '<script language="javascript">document.location.replace("connexion");</script>';
																						exit();
																						} else {
																							$erreur = "Veuillez saisir une r??ponse.";
																						}
																					} else {
																						$erreur = "Le pays ne doit pas d??passer 50 caract??res !";
																					}
																				} else {
																					$erreur = "Veuillez saisir un pays.";
																				}
																			} else {
																				$erreur = "Veuillez saisir une ville";
																			}
																		} else {
																			$erreur = "Format du code postal invalide !";
																		}
																	} else {
																		$erreur = "Veuillez saisir un code postal.";
																	}
																} else {
																	$erreur = "Veuillez saisir une adresse.";
																}
															} else {
																$erreur = "Les mots de passe ne correspondent pas !";
															}
														} else {
															$erreur = "Votre mot de passe doit contenir au moins 1 lettre majuscule, 1 lettre minuscule, 1 chiffre, 1 caract??re sp??cial (@$!&?;%:#+=<>*._-) et 8 caract??res minimum.";
														}
													} else {
														$erreur = "Veuillez saisir un mot de passe.";
													}
												} else {
													$erreur = "Adresse email d??j?? utilis??.";
												}
											} else {
												$erreur = "Adresse email d??j?? utilis??.";
											}
										} else {
											$erreur = "Format de l'adresse email invalide !";
										}
									} else {
										$erreur = "Format de l'adresse email invalide !";
									}
								} else {
									$erreur = "Veuillez saisir une adresse email.";
								}
							} else {
								$erreur = "Ce num??ro de t??l??phone est d??j?? utilis?? !";
							}
						} else {
							$erreur = "Ce num??ro de t??l??phone est d??j?? utilis?? !";
						}
					} else {
						$erreur = "Le num??ro de t??l??phone doit contenir 10 chiffres !";
					}
				} else {
					$erreur = "Le pr??nom doit commencer par une lettre majuscule et ne doit pas d??passer 50 caract??res !";
				}
			} else {
				$erreur = "Veuillez saisir un pr??nom.";
			}
		} else {
			$erreur = "Le nom doit commencer par une lettre majuscule et ne doit pas d??passer 50 caract??res !";
		}
	} else {
		$erreur = "Veuillez saisir un nom.";
	}
}

require_once("vue/inscription-particulier.php");

?>
