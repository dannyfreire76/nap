$(function() {//on doc ready
	if ( $('#base_path').size() > 0 ) {
	
		var thisSiteKey = $('#base_path').val().substring( 6 );//6 = '/home/' 
		thisSiteKey = thisSiteKey.substring( 0, thisSiteKey.indexOf('/') );
	
		incPath = $('#current_base').val() + 'includes/' + thisSiteKey + '/' +  thisSiteKey + '_common.js';
		includeJS( incPath );
	}
});