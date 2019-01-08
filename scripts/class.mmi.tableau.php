<?php

	/**
	 * Classe gestion tableau
	 */
	class Tableau_MMi
	{
		private $tab_displayed = false;
		function __construct($args=array())
		{
			//
		}

		/*
		*	Include du tableau html
		*/
		public function AfficherTableauHTML()
		{
			if($this->tab_displayed)
				exit('ERREUR - Le tableau HTML a deja été affiché ! - fichier:'.__FILE__.' - ligne:'.__LINE__);

			$x = @include('hyperplanning.php');
			if(!$x)
				exit('Le tableau HTML est introuvable - classe:'.__CLASS__.' - ligne:'.__LINE__.' - fichier:'.__FILE__);
			$this->tab_displayed = true;
		}

		/*
		*	Remplissage du tableau
		*/
		public function RemplirTableau($date_cible_edt='')
		{
			if(!$this->tab_displayed)
				exit('ERREUR - Pour remplir le titre du tableau, il faut d\'abord l\'afficher ! - fichier:'.__FILE__.' - ligne:'.__LINE__);

			if(!isset($date_cible_edt) || empty($date_cible_edt))
				$date_cible_edt = date('d/m/Y');

			$promotions = array(	'DUT_1_MMI'=>'edt/EdT_DUT_1_MMI___Temps_plein.ics',
									'DUT_1_MMI_AL'=>'edt/EdT_DUT_1_MMI___Alternance.ics',
									'DUT_2_MMI'=>'edt/EdT_DUT_2_MMI__Temps_plein_FI.ics',
									'DUT_2_MMI_AL'=>'edt/EdT_DUT_2_MMI___Alternance.ics',
									'LP_CVCA'=>'edt/EdT_LP_Comm_et_valor_crea_art.ics'		);

			//	Creation d'un tableau de matieres de toutes les promos a partir du tableau ICS
			$matieres_promo = array();
			foreach ($promotions as $key_promo => $lien_fichier_ics) {
				$traitement = new Traitement_ICS_MMi($lien_fichier_ics);
				$matieres_promo[$key_promo] = $traitement->get_matieres_par_date( $date_cible_edt );
			}
			unset($traitement);
	
			//	Pour chaque matiere de chaque promo, on recup et transforme des variables & on affiche
			$mmiTools = new MMi_ToolsClass();
			foreach ($matieres_promo as $key_promo => $matieres_promo) {			// Pour chaque promo
				foreach ($matieres_promo as $key_matiere_promo => $matiere_data) {	// Pour chaque matiere

					//	Matière
					$mat['salle'] = @explode(' - ', $matiere_data['LOCATION'])[0];
					$mat['type'] = $mmiTools->get_type_cours_name_cours( $matiere_data['SUMMARY'] );
					$mat['initiales_prof'] = $mmiTools->get_initiales_profs( $matiere_data['SUMMARY'] );
					$mat['groupe_eleve'] = $mmiTools->get_groupe_eleve_by_full_name_cours( $matiere_data['SUMMARY'] );
					$mat['nom'] = $mmiTools->get_matiere_cours_by_full_name_cours( $matiere_data['SUMMARY'] );
					$mat['heure_debut'] = date("H-i", strtotime( $matiere_data['DTSTART'] ) );
					$mat['duree'] =	$mmiTools->difference_heure_to_int( $matiere_data['DTEND'],$matiere_data['DTSTART'], 'return_duree_str' );

					//	Cellule
					$cellule['nom_affichage'] = $mmiTools->satinizeString($mat['initiales_prof'].' - '.$mat['nom'].' - '.$mat['salle'].' ('.$mat['type'].')');
					$cellule['numero_ligne'] = $this->getInfo_of_promo_by_key($key_promo, $mat['groupe_eleve'], $info_to_return='numero_ligne_tableau');
					$cellule['rowspan'] = $this->getInfo_of_promo_by_key($key_promo, $mat['groupe_eleve'], $info_to_return='rowspan_classe_entiere');
					$cellule['colspan'] = $mmiTools->difference_heure_to_int( $matiere_data['DTEND'],$matiere_data['DTSTART'], 'return_nb_colspan' );

					//	Modal
					$modal['location'] = $mmiTools->satinizeString( $matiere_data['LOCATION'] );
					$modal['summary'] = $mmiTools->satinizeString( $matiere_data['SUMMARY'] );
					$modal['description'] = $mmiTools->satinizeString( $matiere_data['DESCRIPTION'] );
					$modal['dtstart'] = date("H:i", strtotime( $mmiTools->satinizeString( $matiere_data['DTSTART'] ) ) );
					$modal['dtend'] = date("H:i", strtotime( $mmiTools->satinizeString( $matiere_data['DTEND'] ) ) );
					$modal['duree'] = $mmiTools->difference_heure_to_int( $matiere_data['DTEND'],$matiere_data['DTSTART'], 'return_duree_str' );
					

					//	Affichage matière dans le tableau
					echo '<script type="text/javascript">'."";
						echo "ajouterMatiere(".$cellule['numero_ligne'].", '".$mat['heure_debut']."', '".$cellule['colspan']."', '".$cellule['nom_affichage']."', '".$cellule['rowspan']."', '".$modal['location']."', '".$modal['summary']."', '".$modal['dtstart']."', '".$modal['dtend']."', '".$modal['duree']."', '".$modal['description']."');";
					echo "</script>\n";


					//	DEBUG (dev)
					if(false)
					{
						echo '<ul>';
							echo '<li>Promotion : '.$key_promo.'</li>';
							echo '<li>Type : '.$mat['type'].'</li>';
							echo '<li>Initiales professeurs : '.$mat['initiales_prof'].'</li>';
							echo '<li>Salle : '.$mat['salle'].'</li>';
							echo '<li>Groupe élève : '.$mat['groupe_eleve'].'</li>';
							echo '<li>Nom : '.$mat['nom'].'</li>';
							echo '<li>Durée : '.$mat['duree'].'</li>';
							echo '<li>Date : ';
								echo "".date("d/m/Y", strtotime( $matiere_data['DTSTART'] ) );
								echo " de ".date("H:i", strtotime( $matiere_data['DTSTART'] ) )." à ".date("H:i", strtotime( $matiere_data['DTEND'] ) );
							echo '</li>';
							echo '<li>Nom VEVENT : '.$matiere_data['SUMMARY'].'</li>';
						echo '</ul>';
						echo '<hr>';
					}

					//	Liberation variables	
					unset($modal);
					unset($mat);
					unset($cellule);
				}							//END FOREACH MATIERE
			}								//END FOREACH PROMO
		}									//END FUNCTION

		/*
		*	Retourne a quelle ligne doit etre placée une matiere en fonction de son identifiant promo et du groupe d'élève (A, B, C...)
		*/
		private function getInfo_of_promo_by_key($key_promo, $mat_groupe_eleve, $info_to_return='numero_ligne_tableau')
		{
			$index_promo=1;
			$rowspan_classe_entiere=1;
			if($key_promo=="DUT_1_MMI")
			{
				
				switch (strtolower($mat_groupe_eleve)) {
					case 'dut1 mmi gpe a1':
						$index_promo=1;
						break;
					case 'dut1 mmi gpe a2':
						$index_promo=2;
						break;
					case 'dut1 mmi gpe b1':
						$index_promo=3;
						break;
					case 'dut1 mmi gpe b2':
						$index_promo=4;
						break;
					case 'dut1 mmi gpe c1':
						$index_promo=5;
						break;
					case 'dut1 mmi gpe c2':
						$index_promo=6;
						break;
					case 'dut1 mmi a':
						$index_promo=1;
						break;
					case 'dut1 mmi b':
						$index_promo=3;
						break;
					case 'dut1 mmi c':
						$index_promo=5;
						break;
					default:
						$index_promo=1;
						$rowspan_classe_entiere = 6;
						break;
				}
			}
			else if($key_promo=="DUT_2_MMI")
			{
				
				switch (strtolower($mat_groupe_eleve)) {
					case 'dut2 mmi gpe a1':
						$index_promo = 7;
						break;
					case 'dut2 mmi gpe a2':
						$index_promo=8;
						break;
					case 'dut2 mmi gpe b1':
						$index_promo=9;
						break;
					case 'dut2 mmi gpe b2':
						$index_promo=10;
						break;
					case 'dut2 mmi gpe c1':
						$index_promo=11;
						break;
					case 'dut2 mmi gpe c2':
						$index_promo=12;
						break;
					case 'dut2 mmi a':		//Seul ces trois noms sont utilisés dans mmiplanning
						$index_promo=7;
						break;
					case 'dut2 mmi b':
						$index_promo=9;
						break;
					case 'dut2 mmi c':
						$index_promo=11;
						break;
					default:
						$index_promo = 7;
						$rowspan_classe_entiere = 6;
						break;
				}
			}
			else if($key_promo=="DUT_1_MMI_AL")	//A tester
			{
				
				switch (strtolower($mat_groupe_eleve)) {
					case 'tp a':
						$index_promo = 13;
						break;
					case 'tp b':
						$index_promo=14;
						break;
					default:
						$index_promo = 13;
						$rowspan_classe_entiere = 2;
						break;
				}
			}
			else if($key_promo=="DUT_2_MMI_AL")
			{
				
				switch (strtolower($mat_groupe_eleve)) {
					case 'tp a':
						$index_promo = 15;
						break;
					case 'tp b':
						$index_promo=16;
						break;
					default:
						$index_promo = 15;
						$rowspan_classe_entiere = 2;
						break;
				}
			}
			else 	//	LP_CVCA
			{
				
				switch (strtolower($mat_groupe_eleve)) {
					case 'alpha':					//Y a til des groupes en CVCA?
						$index_promo = 17;
						break;
					case 'beta':
						$index_promo=17;
						break;
					case 'charlie':
						$index_promo=17;
						break;
					default:
						$index_promo = 17;
						$rowspan_classe_entiere = 2;
						break;
				}
			}


			if($info_to_return=='numero_ligne_tableau')		//Retourne a quelle ligne est placée une matière
				return $index_promo;
			else 											//Retourne le rowspan classe entiere
				return 	$rowspan_classe_entiere;			//si groupe=='classe entiere' alors rowspan!=1
		}

		/*
		*	Retourne quel jour doit etre affiché dans le tableau (d/m/Y)
		*/
		public function getDateCibleEdt()
		{
			$d = date('d/m/Y');
			if(isset($_GET['cible']))
				$d = $_GET['cible'];

			return $d;
		}

		/*
		*	Changement du titre du tableau (JS)
		*/
		public function UpdateTitreTableau($nouv_titre)
		{
			if(!$this->tab_displayed)
				exit('ERREUR - Pour modifier le titre du tableau, il faut d\'abord l\'afficher ! - fichier:'.__FILE__.' - ligne:'.__LINE__);

			?>
			<script type="text/javascript">
				$('#c_tab_titre').text('<?php echo $nouv_titre; ?>');
			</script>

			<?php
		}

		/*
		*	Permet d'ameliorer le visuel du tableau, fusionner les cellules vides... (JS)
		*/
		public function CacherCellulesVides()
		{
			if(!$this->tab_displayed)
				exit('ERREUR - Pour modifier le titre du tableau, il faut d\'abord l\'afficher ! - fichier:'.__FILE__.' - ligne:'.__LINE__);

			echo '<script type="text/javascript">'."";
				echo "cacherCellulesVides();";
			echo "</script>\n";	

		}
		
	}	//END CLASS