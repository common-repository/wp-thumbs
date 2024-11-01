jQuery(document).ready(function($){
$("#wp-thumbs-like-button").live('click',function(){
    var id=$(this).attr('alt');
$.ajax({
			type: "POST",
			url:  "/wp-content/plugins/wp-thumbs/register.php",
            data: { data: "like", id: id },
			success: function(returndata){
                if(returndata == 'no') {
                } else {
                     $('#wp-thumbs-post-'+id).load('/wp-content/plugins/wp-thumbs/ajax.php', { 'ajax': "reload",'post': id });
                     $('#wp-thumbs-message-'+id).show('slow').delay("1000").hide("slow");
                }
               }
    	});  
});

$("#wp-thumbs-dislike-button").live('click',function(){    
    var id=$(this).attr('alt');
$.ajax({
			type: "POST",
			url:  "/wp-content/plugins/wp-thumbs/register.php",
            data: { data: "dislike", id: id },
			success: function(returndata){
                if(returndata == 'no') {
                } else {
                     $('#wp-thumbs-post-'+id).load('/wp-content/plugins/wp-thumbs/ajax.php', { 'ajax': "reload",'post': id });
                     $('#wp-thumbs-message-'+id).show('slow').delay('1000').hide('slow');
                }
               }
    	});
});
});
