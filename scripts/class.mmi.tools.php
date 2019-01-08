<?php

	/**
	* 	Ensemble de fonctions utiles pour l'affichage et la transformation de données du tableau
	*/
	class MMi_ToolsClass
	{
		private $profs = array(	'ADOUANE Karim'=>'kad', 
								'ISCARIOT Nicolas'=>'isc', 
								'TOZZA Jean rene'=>'jrt', 
								'BARTHES Isabelle'=>'iba', 
								'PESSEL Nathalie'=>'pes', 
								'MITRE Laurent'=>'lmi', 
								'SENISAR Sandra'=>'sse', 
								'LACHAUME Christophe'=>'cl', 
								'BEN KHELIFA Mohamed moncef'=>'mbk', 
								'FINO Corinne'=>'fin', 
								'MARTIN Alexia'=>'am', 
								'DELANNOY Elodie'=>'del', 
								'AMATO Stephane'=>'sam',			//? 
								'TORCHE MAELYS'=>'mto', 
								'TORCHE Maiylis'=>'mto', 			//Mal orthographié dans edt/gware
								'BREANDON Christine'=>'cb', 
								'SHIRI Soundous'=>'ssh', 			//?
								'RAVIOLO Jean-paul'=>'jpr', 
								'KHALED Maamar'=>'mkh', 
								'COLLIN Sandra'=>'sco', 
								'MIDAVAINE Valerie'=>'vmi', 
								'MOTTO Pascale'=>'pmo', 
								'DUNE MAILLARD Claire'=>'cd', 
								'NGUYEN Thanh phuong'=>'tn', 
								'BAILLEUL Florent'=>'fba', 
								'ERRIGO Salvy'=>'ser', 				//?
								'SCHELL Linda'=>'lsc', 				//?
								'SEILHES Manuel'=>'mse', 
								'ALI BAKO Ousmane'=>'ali', 
								'VAN HOVE Magali'=>'mvh', 
								'LATETE Regis'=>'rla', 
								'TSCHAINE Ludivine'=>'lts', 		//?
								'FRANCOIS Alexandre'=>'afr', 		//?
								'LE DRUILLENNEC Christian'=>'dle',	//? 
								'R.LATETE'=>'rla', 					//? 
								'BULINGE Franck'=>'fbu');			//?
		
		private $matieres = array( 	'Réseau 1'=>'res1',
									'Intégration web 1'=>'web1',
									'Prod audiovisuelle 1'=>'prod1',
									'Anglais 1'=>'ang1',
									'Algorithme 1'=>'algo1',
									'Infographie 1'=>'info1',
									'Adaptation UE1 1'=>'ada ue1 1',
									'Gestion de projet 1'=>'proj1',
									'Adaptation UE2 1'=>'ada ue2 1',
									'PPP 1'=>'ppp1',
									'Ecriture numérique 1'=>'ecrit1',
									'Expression artistique 1'=>'art1',
									'Science 1'=>'sci1',
									'Expression communica 1'=>'com1',
									'Droit Eco Mercatique 1'=>'eco1',
									'Information Communica 1'=>'com1',
									'Droit Eco Mercatique 2'=>'eco2',
									'Expression communica 2'=>'com2',
									'Prod audiovisuelle 1'=>'prod1',
									'Anglais 2'=>'ang2',
									'Réseau 2'=>'res2',
									'Ecriture numérique 2'=>'ecrit2',
									'Prod audiovisuelle 2'=>'prod2',
									'Expression communica. 3'=>'com3',
									'Réal. tournage capt. cam.'=>'Réal. tournage capt. cam.',
									'Ingé. réseaux info.'=>'Ingé. réseaux info.',
									'Théorie du signal audio.'=>'Théorie du signal audio.',
									'Scénario fiction websérie'=>'Scénario fiction websérie',
									'Conception publicitaire'=>'Conception publicitaire',
									'Anglais technique audio.'=>'Anglais technique audio.',
									'PPP'=>'PPP',
									'Intégration web'=>'web',
									'Science'=>'sci',
									'Réseaux'=>'res',
									'Anglais'=>'ang',
									'Information communication'=>'com',
									'Droit économie mercatique'=>'eco',
									'Infographie'=>'info',
									'Ecriture numérique'=>'ecrit',
									'Programmation objet'=>'poo',
									'Expression communication'=>'com',
									'Développement web'=>'web',
									'Expression artistique'=>'art',
									'Gestion de projet'=>'proj',
									'Production audiovisuelle'=>'prod',
									'Environmt jur\, éco & merc'=>'ecomerca',

									'VACANCES'=>'VACANCES',
									'STAGE'=>'STAGE',
									'Matière à préciser'=>'Matière à préciser',
								);

		function __construct($args=array())
		{
			//
		}

		/*
		* A partir du titre du VEVENT, on en deduit l'initiale de l'enseignant
		*/
		public function get_initiales_profs($nom_complet_matiere)
		{
			// Si le titre de l'event contient le nom d'un enseignant de la liste, on retourne ses initiales
			$nom_complet_matiere = strtolower($nom_complet_matiere);
			$nom_complet_matiere = preg_replace('/\s+/', ' ', trim($nom_complet_matiere));
			foreach ($this->profs as $nom_complet => $initiales) {
				if(strpos($nom_complet_matiere, strtolower($nom_complet)) !== false)
					return $initiales;
			}

			return "";
		}


		/*
		*	A partir du nom complet du VEVENT, on en deduit le type de cours (TP/TD/CM)
		*/
		public function get_type_cours_name_cours($nom_complet_du_cours)
		{
			$nom_complet_du_cours = strtolower($nom_complet_du_cours);
			$nom_complet_du_cours = str_replace('é', 'e', $nom_complet_du_cours);
			$nom_complet_du_cours = str_replace('è', 'e', $nom_complet_du_cours);
			$nom_complet_du_cours = str_replace('à', 'a', $nom_complet_du_cours);
			$nom_complet_du_cours = preg_replace('/\s+/', ' ', trim($nom_complet_du_cours));
			switch (true) {
				case strpos($nom_complet_du_cours, '- td'):
					return "TD";
					break;
				case strpos($nom_complet_du_cours, '- tp'):
					return "TP";
					break;
				case strpos($nom_complet_du_cours, '- cours (cm)'):
					return "CM";
					break;
				default:
					return 'Cours';
					break;
			}
		}

		/*
		*	A partir du nom complet du VEVENT, on en deduit les initiales de la matière
		*/
		public function get_matiere_cours_by_full_name_cours($nom_complet_matiere)
		{
			// Si le titre de l'event contient le nom d'un cours, alors on retourne ce nom
			$nom_complet_matiere = strtolower($nom_complet_matiere);
			$nom_complet_matiere = preg_replace('/\s+/', ' ', trim($nom_complet_matiere));
			foreach ($this->matieres as $nom_complet => $initiales_matiere) {
				if(strpos($nom_complet_matiere, strtolower($nom_complet)) !== false)
					return $initiales_matiere;
			}
			//Sinon on retourne le nom complet
			return $nom_complet_matiere;
		}

		/*
		*	A partir du nom complet du VEVENT, on en deduit le groupe élève
		*/
		public function get_groupe_eleve_by_full_name_cours($nom_complet_du_cours)
		{
			$tab_groupes = array(	'dut1 mmi gpe a1',					//plus precis dabord
									'dut1 mmi gpe a2',
									'dut1 mmi gpe b1',
									'dut1 mmi gpe b2',
									'dut1 mmi gpe c1',
									'dut1 mmi gpe c2',
									'dut2 mmi gpe a1',
									'dut2 mmi gpe a2',
									'dut2 mmi gpe b1',
									'dut2 mmi gpe b2',
									'dut2 mmi gpe c1',
									'dut2 mmi gpe c2',
									'dut1 mmi a',
									'dut1 mmi b',
									'dut1 mmi c',
									'dut2 mmi a',
									'dut2 mmi b',
									'dut2 mmi c',
									'tp a',
									'tp b',	
									'alpha',						//groupes CVCA
									'bravo',	
									'charlie',	
									'vacances',						//Vacances, Stage...
									'stage',	
									'rentree',
									'matiere a preciser'	
								);
			$nom_complet_du_cours = strtolower($nom_complet_du_cours);
			$nom_complet_du_cours = str_replace('é', 'e', $nom_complet_du_cours);
			$nom_complet_du_cours = str_replace('è', 'e', $nom_complet_du_cours);
			$nom_complet_du_cours = str_replace('à', 'a', $nom_complet_du_cours);
			$nom_complet_du_cours = preg_replace('/\s+/', ' ', trim($nom_complet_du_cours));

			//on parcourt le tableau des groupes
			foreach ($tab_groupes as $index => $groupe) {
				if(strpos($nom_complet_du_cours, $groupe) !== false)
					return strtoupper($groupe);
			}

			//Cours speciaux
			if(	strpos($nom_complet_du_cours, 'vacance') !== false ||
				strpos($nom_complet_du_cours, 'stage') !== false ||
				strpos($nom_complet_du_cours, 'rentree') !== false ||
				strpos($nom_complet_du_cours, 'matiere a preciser') !== false ||
				strpos($nom_complet_du_cours, 'soutenance') !== false)
			{
				return "";
			}
			return "CLASSE ENTIERE";
		}

		/*
		*	Retourne la difference de deux timestamps UNIX TZ
		*/
		public function difference_timestamp($qw,$saw)
		{
		    $datetime1 = new DateTime("@$qw");
		    $datetime2 = new DateTime("@$saw");
		    $interval = $datetime1->diff($datetime2);
		    $duree_h = $interval->format('%H');
		    $duree_m = $interval->format('%I');
		    if($duree_m != '0')
		    	return $duree_h.'.5';
		    else
		    	return $duree_h;
		}

		/*
		*	Pour une chaine de charactère donnée, supprime les indésirables pour inclusion dans du JS
		*/
		public function satinizeString($string)
		{
			$string = str_replace('"', '', $string);
			$string = str_replace("'", '', $string);
			$string = preg_replace('/\s+/', ' ', trim($string));

			return $string;
		}

		/*
		*	Retourne le nombre de colspan a partir des timestamp debu/fin d'un event
		*/
		public function difference_heure_to_int($dtend, $dtstart, $data_to_return='return_nb_colspan')
		{
			$dt_end['heure'] = intval( date("H", strtotime($dtend)) );
			$dt_end['min'] = intval( date("i", strtotime($dtend)) );

			$dt_start['heure'] = intval( date("H", strtotime($dtstart)) );
			$dt_start['min'] = intval( date("i", strtotime($dtstart)) );

			//	Calcul de la difference
			$diff['heure'] = intval($dt_end['heure']-$dt_start['heure']);
			$diff['min'] = intval($dt_end['min']-$dt_start['min']);
			if($diff['min']<0)
			{
				$diff['min'] = intval($diff['min'] * -1);
				$diff['heure']--;
			}


			//	Conversion de la difference en un colspan (1colspan = 30min)
			$diff_total_minutes = $diff['heure']*60 + $diff['min'];
			$nb_colspan = intval($diff_total_minutes/30);

			if($data_to_return=='return_nb_colspan')
				return $nb_colspan;
			else
				return $diff['heure'].'h'.$diff['min'];
		}

	}