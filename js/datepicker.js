
  $( function() {
    $( "#datepicker" ).datepicker();
  } );

 jQuery(function($){
	$.datepicker.regional['fr'] = {
		closeText: 'Fermer',
		nextText: 'Suiv&#x3e;',
		prevText: '&#x3c;Préc',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier','Fevrier','Mars','Avril','Mai','Juin',
		'Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
		monthNamesShort: ['Jan','Fev','Mar','Avr','Mai','Jun',
		'Jul','Aou','Sep','Oct','Nov','Dec'],
		dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
		dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
		dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
		weekHeader: 'Sm',
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: true,
		showMonthAfterYear: false,
		yearSuffix: '',
		minDate: 0,
		maxDate: '+2M +0D',
		numberOfMonths: 1,
		showButtonPanel: true
		};
	$.datepicker.setDefaults($.datepicker.regional['fr']);
});