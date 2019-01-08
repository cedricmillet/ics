	/*
		hyperplanning.js
	*/

	
	//===> Ajouter une matière au tableau
	function ajouterMatiere(num_ligne, heure, colspan, texte, rowspan=1, ics_location='', ics_summary='', ics_dtstart='', ics_dtend='', ics_duree_calculee='', ics_description='')
	{
		heure = heure.replace('h', '-');
		
		//console.log('AddMatiere - Ligne n°'+num_ligne+' - heure:'+heure+' - colspan:'+colspan+' - texte: '+texte);
		if($('#l'+num_ligne+'_'+heure).length)
		{
			if(!cell_is_empty($('#l'+num_ligne+'_'+heure)))
			{
				//arrive lors de changement de salle ??
				console.log('Une matière a déja été placée pour la ligne n°'+num_ligne+' à l\'heure '+heure);
				console.log('Cette matière devait etre placée : '+texte);
				console.log('');
			}
			else
			{
				//Fusion cellule
				mergeCell('#l'+num_ligne+'_'+heure, colspan, rowspan, false);
				//Update
				var cell = $('#l'+num_ligne+'_'+heure);
				cell.addClass('matiere');	
				cell.text(texte);
				cell.data('location', ics_location);
				cell.data('summary', ics_summary);
				cell.data('dtstart', ics_dtstart);
				cell.data('dtend', ics_dtend);
				cell.data('duree', ics_duree_calculee);
				cell.data('description', ics_description);
			}
		}
		else
		{
			console.log('Ligne introuvable : '+'#l'+num_ligne+'_'+heure+' - heure:'+heure+' - colspan:'+colspan+' - texte: '+texte)
		}
		
	}


	//===> Fusionner une cellule du tableau
	function mergeCell(selector, colspan, rowspan, DEBUG_MERGE=false)
	{
		//console.log(selector+' - col:'+colspan+' | rows:'+rowspan)

		//	FUSION COLSPAN
		for(var i=1; i<colspan; i++)
		{
			if(DEBUG_MERGE)
				console.log('COLSPAN: Supression de : '+$(selector).next().attr('id'));
			$(selector).next().remove();
		}
		$(selector).attr('colspan', colspan);
		
		//	FUSION ROWSPAN
		if(rowspan>1)
		{
			var all_cells_above_are_empty = true;
			var heure_debut = $(selector).attr('id').split('_')[1];

			//check si toutes les cellules requises sont dispo
			for (var r = 1; r < rowspan; r++) {
				var i_ligne = parseInt($(selector).attr('id').replace('l', '').split('_')[0])+r; //numero de ligne
				var selector_last_cell = $('#l'+i_ligne+'_'+heure_debut);
				for (var c = 1; c <= colspan; c++) {
					if(!cell_is_empty(selector_last_cell))
					{
						all_cells_above_are_empty = false;
						break;
						//console.log('Une cellule est pleine : '+selector_last_cell.attr('id'));
					}
					selector_last_cell.css('background-color', 'orange');

					
					selector_last_cell = selector_last_cell.next();
					//console.log('Rowspan '+r+'/'+rowspan+' - Col'+c+'/'+colspan+' - ligne N°'+i_ligne)
				}
			}

			//	Si toutes les cellules sont vides on les fusionne
			if(all_cells_above_are_empty)
			{
				//	Ajout balise à toutes les cellules à supprimer
				for (var r = 1; r < rowspan; r++) {
					var i_ligne = parseInt($(selector).attr('id').replace('l', '').split('_')[0])+r; //numero de ligne
					var selector_last_cell = $('#l'+i_ligne+'_'+heure_debut);
					for (var c = 1; c <= colspan; c++) {
						selector_last_cell.css('background-color', 'red');
						selector_last_cell.addClass('to_delete');
						selector_last_cell = selector_last_cell.next();
					}
				}
				//	Ajout rowspan
				$(selector).attr('rowspan', rowspan);

				//	Suppression de toutes les cellules avec balise 'to_delete'
				$('table').find('td').each (function() {
  					if($(this).hasClass('to_delete'))
  						$(this).remove();
				});     
			}
			
		}
	}


	//===> Retourne true si la cellule spécifiée est vide
	function cell_is_empty(selector_cell)
	{
		if(selector_cell.length)
		{
			if(selector_cell.hasClass('matiere'))
				return false;
			else
				return true;
		}
		//console.log('La cellule '+selector_cell+' est inexistante.');
		return false;
	}

	//===> Cacher les cellules vides du tableau
	function cacherCellulesVides()
	{
		var tab_nbrow = 18;
		var tab_nbcol = 29;
		var utiliser_methode_css = true;
		//On conservera les cellules et au lieu de fusionner les cellules vide on leur mettra un border-color transparent
		//Ainsi on ne detruit pas des cellules vides dans lesquelles ont souhaitera peut etre ajouter des matieres

		if($('table#c_hyperplanning').length)
		{
			if(utiliser_methode_css)		//--	METHODE 1 - CSS
			{
				$('table').find('td').each (function() {
					//Si cellule valide && au moins deux cellules vides sont collées
					if($(this).hasClass('tg-c3ow') 
						&& cell_is_empty($(this)))
					{
						//Check si on peut supprimer bordure de droite
						if(cell_is_empty($(this).next()))
							$(this).css('border-right-color', 'transparent');

						//Check si on peut supprimer bordure du bas
						if(getBottomCell_ofCell($(this)) != false)
						{
							if(cell_is_empty(getBottomCell_ofCell($(this))))
								$(this).css('border-bottom-color', 'transparent');
							//getBottomCell_ofCell($(this)).css('background-color', 'red');
						}
					}
				});
			}
			else							//--	METHODE 2 - Fusion des cellules
			{
				$('table').find('td').each (function() {
					//Si cellule valide && au moins deux cellules vides sont collées
					if($(this).hasClass('tg-c3ow') 
						&& cell_is_empty($(this))
						&& cell_is_empty($(this).next())
						)
					{
						var start_cell = $(this);
						var colspan = 2;


						//Recherche de l'étendue vide
						var temp_sel = $(this).next();
						while(cell_is_empty(temp_sel))
						{
							for(var x=1;x<=colspan; x++)
							{
								if(cell_is_empty(temp_sel))
								{
									temp_sel = temp_sel.next();
									colspan++;
								}
								else
									break;
							}
						}
						colspan--;
						console.log('colspanmax = '+colspan);


						//Suppression
						
						temp_sel = $(this).next();
						for(var x=2; x<=colspan; x++)
						{
							var t = temp_sel.next();
							temp_sel.css('background-color', 'red');
							temp_sel.remove();
							
							temp_sel = t;
						}
						$(this).attr('colspan', colspan);

						//Dev = 1 seule
						
					}
				});
			}
			tab_messageSiVide();
		}		
	}	//END function

	//===> Affiche un message sur le tableau si le tableau est vide
	function tab_messageSiVide()
	{

		var toutes_cellules_sont_vides = true;
		$('table#c_hyperplanning').find('td').each (function() {
			if($(this).hasClass('tg-c3ow'))
			{
				if(!cell_is_empty($(this)))
				{
					toutes_cellules_sont_vides = false;
					return false;
				}
			}
		});

		if(toutes_cellules_sont_vides==true)
		{
			console.log('cellules toutes vides !');
			$('table#c_hyperplanning').find('td').each (function() {
				if($(this).hasClass('tg-c3ow'))
				{
					if($(this).attr('id') == 'l1_08-00')
					{
						$(this).html('Aucun cours trouvé.<br><br>Utilisez le clic-droit pour changer de jour.');
						$(this).css('border', '1px solid black')
						$(this).css('font-size', '26px')
					}
					else
					{
						$(this).remove();
					}
					
					//$(this).css('background-color', 'cyan');
				}
			});
			$('td#l1_08-00').attr('colspan', '24');
			$('td#l1_08-00').attr('rowspan', '18');
		}
		
		
	}

	//===> Retourne le selecteur de la cellule en dessous de la cellule cible
	function getBottomCell_ofCell(cell)
	{
		if(cell.length)
		{
			var current_id = cell.attr('id');
			var current_ligne = current_id.split('_')[0].replace('l', '');
			var current_heure = current_id.split('_')[1];

			var bottom_ligne = parseInt(current_ligne)+1;
			var bottom_heure = current_heure;
			var cell_bottom = $('#l'+bottom_ligne+'_'+bottom_heure+'');
			if(cell_bottom.length)
				return cell_bottom;
		}
		return false;
	}

	//===> Fermer modal
	function modal_close()
	{
		$('div#c_modal').html('Et je cache ça la...');
		$('div#c_modal').slideUp();
	}


	//======================= USER EVENTS


	//===> Ouverture modal
	$(document).on('click','td', function(event){
	   var modal = $('div#c_modal');
	   if($(this).hasClass('matiere'))
	   {
	   		//Affichage
		   modal.css('display', 'none');
		   modal.slideDown();	//==> modal.css('display', 'block');
		   //Placement
		   modal.css('left', parseInt($(this).offset().left)+'px');
		   modal.css('top', parseInt( $(this).offset().top + parseInt($(this).css('height').replace('px', '')) + parseInt($(this).css('padding-bottom').replace('px', '')*2+2 )+'px'));

		   //Remplissage modal
		   var c_location = $(this).data('location');
		   var c_summary = $(this).data('summary');
		   var c_dtstart = $(this).data('dtstart');
		   var c_dtend = $(this).data('dtend');
		   var c_duree = $(this).data('duree');
		   var c_description = $(this).data('description');
		   var c_html = '<b>Salle :</b> '+c_location+'<br><b>Matière : </b>'+c_summary+'<br><b>Début du cours : </b>'+c_dtstart+'<br><b>Fin du cours : </b>'+c_dtend+'<br><b>Durée calculée</b> : '+c_duree+'<br><b>Description : </b>'+c_description+'<br><br><center><b>→ Cliquez où vous voulez dans ce cadre pour le fermer ←</b></center>';
		   modal.html(c_html);
	   }
	   
	});
	//===> Fermeture modal (clic cellule vide)
	$(document).on('click','td', function(event){
		if($(this).hasClass('matiere')) { return false;}
		if($('div#c_modal').css('display')=='block')
		{
			modal_close();
		}
	});
	//===> Fermeture modal (clic sur modal)
	$(document).on('click','div#c_modal', function(event){
		modal_close();
	});




	//===> Ouverture contextmenu
	$(document).bind("contextmenu", function (event) {
		modal_close();
	    event.preventDefault();
	    $(".context-menu").finish().toggle(100).
	    css({
	        top: event.pageY + "px",
	        left: event.pageX + "px"
	    });
	});
	//===> Cacher contextmenu
	$(document).bind("mousedown", function (e) {
	    if (!$(e.target).parents(".context-menu").length > 0) {
	        $(".context-menu").hide(100);
	    }
	});

	//===> Clic sur item contextmenu
	$(document).on('click','.context-menu li', function(event){
		var href = $(this).data('href');
		window.location = href;
		//$(".custom-menu").hide(100);
	});

	






console.log('>> Chargement hyperplanning.js sans erreur [OK]');