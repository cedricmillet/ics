<?php

	/*
	*	Traitement de fichiers ICS
	*/
	class Traitement_ICS_MMi
	{
		private $ICS_LAST_VERSION_URL = "edt.ics";				// Chemin absolu / URL d'accès au fichier .ics
		private $calendrier_ics;

		function __construct($cal_ics="edt.ics")
		{
			if(isset($cal_ics))
				$this->ICS_LAST_VERSION_URL = $cal_ics;

			date_default_timezone_set('Europe/Madrid'); 	//Conversion timestamp UNIX TZ
			$this->telecharger_derniere_version_ics();
		}

		/*
		*	Mise à jour de la variable globale $calendrier_ics (str), qui contient le contenu du .ics
		*/
		private function telecharger_derniere_version_ics()
		{
			$str = @file_get_contents($this->ICS_LAST_VERSION_URL);
			if($str!=null)
				$this->calendrier_ics = $str;
			else
				$this->exit_with_error("ERREUR - Récupération du contenu du fichier .ics impossible - Fichier:".__FILE__."Fonction:".__FUNCTION__."() - Ligne:".__LINE__);
		}

		/*
		*	Afficher l'emploi du temps sous forme de tableau HTML
		*/
		public function Afficher_Tableau_EDT_Complet()
		{
			if(empty($this->calendrier_ics) || $this->calendrier_ics==null)
				$this->exit_with_error("ERREUR - Vous devez d'abord télécharger un calendrier ICS et le stocker avant d'en extraire des infos - Fichier:".__FILE__."Fonction:".__FUNCTION__."() - Ligne:".__LINE__);

			//======================================= ENTETE TABLEAU
			$data = $this->ics_get_calendrier_data();
			echo 'Début période du calendrier : <b>'.date('d/m/Y H:i:s', strtotime($data['X-CALSTART'])).'</b><br>';
			echo 'Fin période du calendrier : <b>'.date('d/m/Y H:i:s', strtotime($data['X-CALEND'])).'</b><br>';
			echo 'Nom du calendrier : <b>'.$data['X-WR-CALNAME'].'</b><br>';
			echo 'Description du calendrier : <b>'.$data['X-WR-CALDESC'].'</b><br><br>';
			unset($data);
			//======================================= TABLEAU EDT
			$matieres = $this->get_tableau_of_current_ics_str();
			echo '<table border=1>';
			foreach ($matieres as $numero => $data_matiere) {
				/*
					|=====> Cles de $data_matiere:

					'CATEGORIE' => string
					'DTSTAMP' => string
					'LAST-MODIFIED' => string
					'UID' => string
					'DTSTART' => string
					'DTEND' => string
					'SUMMARY' => string
					'LOCATION' => string
					'DESCRIPTION' => string
				*/

				//Variables
				$ligne['matiere_nom'] = $data_matiere['SUMMARY'];
				$ligne['matiere_description'] = $data_matiere['DESCRIPTION'];
				$ligne['matiere_salle'] = $data_matiere['LOCATION'];
				$ligne['matiere_date'] = date("d/m/Y", strtotime( $data_matiere['DTSTART'] ) );
				$ligne['matiere_horaire_debut'] = date("H:i", strtotime( $data_matiere['DTSTART'] ) );
				$ligne['matiere_horaire_fin'] = date("H:i", strtotime( $data_matiere['DTEND'] ) );

				//Affichage ligne
				echo '<tr>';
					echo '<td>'.$ligne['matiere_nom'].'</td>';
					echo '<td>'.$ligne['matiere_salle'].'</td>';
					echo '<td>';
						echo $ligne['matiere_date'].'<br>';
						echo 'de '.$ligne['matiere_horaire_debut'].' à '.$ligne['matiere_horaire_fin'];			
					echo '</td>';
					echo '<td>'.$ligne['matiere_description'].'</td>';
				echo '</tr>';
			}
			echo '</table>';
			//FIN FONCTION AFFICHER
		}

		/*
		*	REcuperation de toutes les matières pour un jour spécifique, pour le ICS ouvert
		*/
		public function get_matieres_par_date($date_cible="")
		{
			if(empty($this->calendrier_ics) || $this->calendrier_ics==null)
				$this->exit_with_error("ERREUR - Vous devez d'abord télécharger un calendrier ICS et le stocker avant d'en extraire des infos - Fichier:".__FILE__."Fonction:".__FUNCTION__."() - Ligne:".__LINE__);

			if(empty($date_cible) || !isset($date_cible))
				$date_cible = date('d/m/Y');

			$tab_to_return = array();
			$c = 0;
			// On parcourt les EVENTS et on construit un tableau avec les EVENT avec date_cible
			$matieres_annee = $this->get_tableau_of_current_ics_str();
			foreach ($matieres_annee as $key_matiere => $data_matiere) {
				$m_date = date('d/m/Y', strtotime( $data_matiere['DTSTART'] ));

				if($m_date === $date_cible)
				{
					foreach ($data_matiere as $key_mat2 => $value) {
						$tab_to_return[$c][$key_mat2] = $value;
					}
					$c++;
				}
			}
			return $tab_to_return;
		}

		/*
		*	Convertir une chaine de charactère (str) en un tableau associatif (array)
		*/
		private function get_tableau_of_current_ics_str()
		{
			if(empty($this->calendrier_ics) || $this->calendrier_ics==null)
				$this->exit_with_error("ERREUR - Vous devez d'abord télécharger un calendrier ICS et le stocker avant d'en extraire des infos - Fichier:".__FILE__."Fonction:".__FUNCTION__."() - Ligne:".__LINE__);

			$tab_resultat = array();
			$matieres = explode('BEGIN:VEVENT', $this->calendrier_ics);
			unset($matieres[0]); //Suppression entete du .ics (contient calname, calstart, caldesc...)
			
			foreach ($matieres as $k => $str) {
				$str = str_replace("END:VEVENT", '', $str);

				preg_match_all("#CATEGORIES:(.*)#", $str, $data['CATEGORIE']);
				$data['CATEGORIE']=@$data['CATEGORIE'][1][0];

				preg_match_all("#DTSTAMP:(.*)#", $str, $data['DTSTAMP']);
				$data['DTSTAMP']=@$data['DTSTAMP'][1][0];

				preg_match_all("#LAST-MODIFIED:(.*)#", $str, $data['LAST-MODIFIED']);
				$data['LAST-MODIFIED']=@$data['LAST-MODIFIED'][1][0];

				preg_match_all("#UID:(.*)#", $str, $data['UID']);
				$data['UID']=@$data['UID'][1][0];

				preg_match_all("#DTSTART:(.*)#", $str, $data['DTSTART']);
				$data['DTSTART']=@$data['DTSTART'][1][0];

				preg_match_all("#DTEND:(.*)#", $str, $data['DTEND']);
				$data['DTEND']=@$data['DTEND'][1][0];
				/*
					NOTE : Lorsque summary est "matière à préciser" alors aucune salle("LOCATION") n'est indiquée, c'est la raison pour laquelle on emploiera l'opérateur @ (qui empechera l'affichage d'erreur et remplira d'un null la variable)

					Par sécurité on utilisera des @ pour toute recuperation d'info sur le ICS, si l'info n'est pas présente alors dans le pire des cas il y aura un blanc sur le tableau
				*/

				preg_match_all("#SUMMARY;LANGUAGE=fr:(.*)#", $str, $data['SUMMARY']);
				$data['SUMMARY']=@$data['SUMMARY'][1][0];

				preg_match_all("#LOCATION;LANGUAGE=fr:(.*)#", $str, $data['LOCATION']);
				$data['LOCATION']=@$data['LOCATION'][1][0];

				preg_match_all("#DESCRIPTION;LANGUAGE=fr:(.*)#", $str, $data['DESCRIPTION']);
				$data['DESCRIPTION']=@$data['DESCRIPTION'][1][0];

				foreach ($data as $data_k => $data_val) {
					$tab_resultat[$k][$data_k] = $data_val;
				}
				unset($data);
			}

			//On tri le tableau par date croissante
			$tab_resultat = $this->ics_trier_cours_tableau($tab_resultat);

			//On retourne le tableau résultat
			return $tab_resultat;
		}

		/*
		*	Trier par date croissante le tableau
		*/
		private function ics_trier_cours_tableau($tab)
		{
			//Variable GLOBALS pour pouvoir changer de nom de classe sans changer le code contenu à l'interieur
			usort($tab, __CLASS__."::comparerParTimestamp");
			return $tab;
		}

		/*
		*	Comparer deux timestamp (pour trier par date croissante)
		*/
		private function comparerParTimestamp($data_time1, $data_time2)
		{
			$time1 = $data_time1['DTSTART'];
			$time2 = $data_time2['DTSTART'];
		    if (strtotime($time1) < strtotime($time2))
		        return -1;
		    else if (strtotime($time1) > strtotime($time2)) 
		        return 1;
		    else
		        return 0;
		}

		/*
		*	Retourne les informations principales de l'entete du .ics (X-CALSTART, X-CALEND, X-WR-CALNAME, X-WR-CALDESC)
		*/
		public function ics_get_calendrier_data()
		{
			if(empty($this->calendrier_ics) || $this->calendrier_ics==null)
				$this->exit_with_error("ERREUR - Vous devez d'abord télécharger un calendrier ICS et le stocker avant d'en extraire des infos - Fichier:".__FILE__."Fonction:".__FUNCTION__."() - Ligne:".__LINE__);

			$tab = array();
			//Début période calendrier
			preg_match_all("#X-CALSTART:(.*)#", $this->calendrier_ics, $tab['X-CALSTART']);
			$tab['X-CALSTART']=$tab['X-CALSTART'][1][0];
			//Fin période calendrier
			preg_match_all("#X-CALEND:(.*)#", $this->calendrier_ics, $tab['X-CALEND']);
			$tab['X-CALEND']=$tab['X-CALEND'][1][0];
			//Nom du calendrier
			preg_match_all("#X-WR-CALNAME;LANGUAGE=fr:(.*)#", $this->calendrier_ics, $tab['X-WR-CALNAME']);
			$tab['X-WR-CALNAME']=$tab['X-WR-CALNAME'][1][0];
			//Description du calendrier
			preg_match_all("#X-WR-CALDESC;LANGUAGE=fr:(.*)#", $this->calendrier_ics, $tab['X-WR-CALDESC']);
			$tab['X-WR-CALDESC']=$tab['X-WR-CALDESC'][1][0];

			return $tab;

		}


		/*
		*	Convertir un timestamp UNIX en une date au format désiré
		*	Nécéssite le changement de timezone en EUROPE/PARIS
		*/
		public function timestamp_to_date($unix_timestamp, $date_format)
		{
			return date("$date_format", strtotime( "$unix_timestamp" ) );
		}

		/*
		*	Arret interpretation PHP + affichage message
		*/
		private function exit_with_error($str)
		{
			if(!empty($str))
				exit($str);
			else
				exit(0);
		}
	}

