<?php

	/**
	 * Classe système de mise en cache de l'emploi du temps
	 */
	class SystemCache_MMi
	{
		private $chemin_dossier_cache = "cache/";
		private $cache_date_tableau = '';
		private $cache_date_is_dispo = false;
		private $cache_refresh = false;

		function __construct()
		{
			$this->cache_date_tableau = $this->getDateCibleCache();

			if(isset($_GET['refresh_cache']))
				$this->cache_refresh = true;

			if($this->PageEnCache())				
				$this->cache_date_is_dispo = true;
			else
				$this->cache_date_is_dispo = false;

		}

		/*
		*	Retourne true si la page actuelle est disponible en cache !
		*/
		public function PageEnCache()
		{
			if($this->cache_refresh)
			{
				//On supprime l'ancien cache
				$filename = $this->getCacheFileNameOfDate($this->cache_date_tableau);
				if(file_exists($this->chemin_dossier_cache.$filename))
					unlink($this->chemin_dossier_cache.$filename);

				//On indique qu'il n'y a pas de cache pour cette page pour en generer une nouvelle
				return false;
			}

			if(!is_writable($this->chemin_dossier_cache))
			{
				echo 'Le dossier '.$this->chemin_dossier_cache.' est inaccessible en écriture. Aucun fichier ne pourra être mit en cache dans la situation actuelle, modifier les chmod du dossier. - CLASS:'.__CLASS__.' - Fonction:'.__FUNCTION__;
				return false;
			}

			$filename = $this->getCacheFileNameOfDate($this->cache_date_tableau);
			if(file_exists($this->chemin_dossier_cache.$filename))
				return true;
			return false;
		}

		/*
		*	Retourne le nom du fichier cache de la date spécifiée
		*/
		private function getCacheFileNameOfDate($date)
		{
			$date = str_replace('/', '-', $date);
			return 'cache_edt___'.date('d-m-Y').'___'.$date.'.html';
		}

		/*
		*	Retourne la date (d/m/Y) du cache demandé
		*/
		private function getDateCibleCache()
		{
			$date = date('d/m/Y');
			if(isset($_GET['cible']))
				$date = $_GET['cible'];
			return $date;
		}

		/*
		*	Affiche la page en cache pour la date courante
		*/
		public function AfficherPageEnCache()
		{
			if(!$this->cache_date_is_dispo)
				exit('Affichage cache à la date '.$this->cache_date_tableau.' impossible. Class:'.__CLASS__.' - fonction: '.__FUNCTION__);

			$filename = $this->getCacheFileNameOfDate($this->cache_date_tableau);
			require_once($this->chemin_dossier_cache.$filename);
		}

		/*
		*	Lance l'enregistrement des sorties HTML
		*/
		public function PreparerMiseEnCache()
		{
			ob_start();
		}

		/*
		*	Met en cache l'enregistrement précédemment lancé
		*/
		public function MettreEnCache()
		{
			if(!is_writable($this->chemin_dossier_cache))
			{
				echo 'Le dossier '.$this->chemin_dossier_cache.' est inaccessible en écriture. Mise en cache impossible - CLASS:'.__CLASS__.' - Fonction:'.__FUNCTION__;
				return false;
			}

			echo '<h2>Tableau généré le '.date('d/m/Y').' à '.date('H:i').'</h2>';
			//	Enregistrement
			$file = $this->chemin_dossier_cache.$this->getCacheFileNameOfDate($this->cache_date_tableau);
			$cached = fopen($file, 'w');
			fwrite($cached, ob_get_contents());
			fclose($cached);
			ob_end_flush();

			//	Si c'était un force_refresh_cache, on redirige vers un lien sans le get[refresh]
			if($this->cache_refresh)
			{
				?>
				<script type="text/javascript">
					var date_cible = (location.search.split('cible' + '=')[1] || '').split('&')[0];
					window.location = window.location.pathname+'?cible='+date_cible;
				</script>
				<?php
			}
		}

		/*
		*	Permet de vider le dossier /cache/
		*/
		public function ViderDossierCache()
		{
			$fichiers_en_cache = scandir($this->chemin_dossier_cache);
			foreach ($fichiers_en_cache as $key => $fichier) {
				if($fichier=='.'||$fichier=='..'||$fichier=='.htaccess') { continue; }
					delete($fichier);
			}
		}

	}