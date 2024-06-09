function Update_etat_caisse()
{
$.ajax({
    url: 'show_caisse.golob',
    success: function(res){
        $("#caisse").text(res);
    }, 
    global: false,     // this makes sure ajaxStart is not triggered, we don't wan to show loader when updating caissse
    dataType: 'html'
});
}


  //========================Ajoute un espace aux valeurs des champs représentant un montant =======
  $('body').on('keyup', 'input.montanth', function(e){
      var value=$(this).val();
      if(value!="")
      {
          var val=value.replace(/\s/g,"");
        //alert(val); 
      val=parseInt(val);
       
      
      val=val.toLocaleString();
       if(val=="NaN")
      {
          alert('Valeur du champ invalide'); die();
      }
      $(this).val(val);
   }
  });
    //=====================GERER LE BOUTON Modification stock ================================
  $('body').on('click','.update_stock',function(e){
      e.preventDefault();
      var id=$(this).attr('id');
      var input='<form action="#" method="post" id="update_stock_form_id">\n\
                Nouvelle Quantité<input type="text" id="update_stock" name="quantite" class="form-control"/>\n\
    <input type="submit" class="btn btn-success pull-right" value="Valider"/>';
      $("#modal").html(input);
      $('#modal').dialog({
          closeText: "hide", 
          close: function( event, ui ) {
              delete input;
              delete id;
              delete window.url;
              $('#modal').html('');
          }});
      $('body').on('submit', "#update_stock_form_id", function(e){
          e.preventDefault();
          var url="";
                url="stock_update-"+id+"-"+$('#update_stock').val()+".golob";
                 
          $.get(url,'',function(response){
             
              showAlert(response); 
              delete input;
              delete id;
              delete window.url;
              $('#modal').dialog('close');
          });
      })
      
  });
  
$(document).ready(function(){
// actualiser l'état de la caisse toutes les cinq minutes 
    setInterval('Update_etat_caisse()',50000);
    // récupération des produits à suggérer pour l'autocomplete
    //alert('same page');
var donnees1=[];
$.ajax({
	'async': true,
	 url: "produit_liste_autocmplete",
	 dataType: "text",
	 success: function(data){
		donnees1=data.split(",");
	}, global: false,
	 error: function(){ alert("Erreur lors du chargement des produits pour les suggestions");},
	 complete: function(){ 
             
             $("body").on('focus','input.auto_com',function(){ 
               $( function() {
                 $( "input.auto_com" ).autocomplete({
                  source: function(request, response) {
                 var results = $.ui.autocomplete.filter(donnees1, request.term);
                response(results.slice(0, 10));
                }
                });
              } );
                         });
                $("body input.typeahead").typeahead({
		name: 'accounts',
		local: donnees1
		});
                     },
        
 });      
//================ENd of ready
});
    
