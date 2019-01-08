<?php
	/*******************************************************
	Nom ......... : hyperplanning v2
	Role ........ : Affichage EDT de toutes les promos sur un tableau
	Auteur ...... : Cédric.M
	Version ..... : V1.2 du 15/9/2018 (+systeme cache)
	********************************************************/

	/*
		/// PATCHNOTE / TO DO LIST ///
			- 2 matières par créneau (changement salle)
			- responsive + design
			- Système de mise en cache (necessitera droit d'ecriture dans un dossier /cache/):
			- Dans les edt hyperplannings il n'y a pas de distinction entre mmi a1, mmi a2... il y a juste mmi a, mmi b...
			- hyperplanning.js ligne 15 (doublon créneau)
			- ajouter jour de la semaine pour mieux se repérer

		/// NOTE ///
			Le système de récupération des infos (initiales enseignant, initiales matières, groupe eleve...) repose sur la présence
			ou non de chaines de charactères spécifiques. Si dans l'avenir la nomenclature des matières contenues dans les .ics hyperplanning change ou si les infos à récupérer ne sont plus au meme endroit qu'avant dans le .ics, les fonctions des classes MMi_tools et Traitement_ICS ne sauront pas récupérér les infos. 
			Donc en plus de ne pas être optimisé, il n'y a aucune ganrantie en ce qui concerne le fonctionnement du projet dans l'avenir, des corrections seront à apporter... 
			La grande difficulté est d'afficher des données valables avec des données écrites par des humains.
	*/
	

	//	Classes
	require_once('scripts/class.mmi.traitement.ics.php');
	require_once('scripts/class.mmi.tools.php');
	require_once('scripts/class.mmi.tableau.php');
	require_once('scripts/class.mmi.cache.php');


	$activer_systeme_cache = true;
	$cache = new SystemCache_MMi;
	//$cache->ViderDossierCache();
	if($cache->PageEnCache() && $activer_systeme_cache)
		$cache->AfficherPageEnCache();
	else
	{
		//	Début mise en cache
		$cache->PreparerMiseEnCache();
			//	Génération & Affichage du tableau HTML
			$hyperplanning = new Tableau_MMi;
			$date_cible = $hyperplanning->getDateCibleEdt();
			$hyperplanning->AfficherTableauHTML();
			$hyperplanning->UpdateTitreTableau('EDT du '.$date_cible);
			$hyperplanning->RemplirTableau($date_cible);
			$hyperplanning->CacherCellulesVides();
			//	Fin génération
		$cache->MettreEnCache();
		//	Fin mise en cache
	}

	