//===========APPLYING DATE PICKER TO ALL DATE INPUT FIELDS======================
//===========                                             =====================

$('body input:text').on('click','.datepicker',function(e){
    $(this).datepicker({dateFormat: 'yy-mm-dd'});
    });
//====================TRAITEMENT DU MENU VERTICAL DES MODULES==========
$('body').on('click','.go_sms_vertical_menu', function(e){
         e.preventDefault();
          $.get($(this).attr('href'),'', function(reponse){
             $('#content_body').html(reponse);});
     });
     
 //============ Envoie par AJAX de tous les formulaires des entités ==========
 $('body').on('submit','[id$="bundle"], [id$="type"]', function(e){
         e.preventDefault();
         var id_form=$(this).attr('id');
         var url=$(this).attr('action');
         $.post($(this).attr('action'), $(this).serialize(), function(res){
             
             if(id_form.match('search'))
             {
                 $('#search_result').html(res);
             }else
             {
                 var rep=showAlert(res, 2000);
             }
            if(res.code==1)
             {
                 resetForm(id_form);
             }
        });
     });
  //========================Ajoute un espace aux valeurs des champs représentant un montant =======
  $('body').on('keyup', 'input.montant', function(e){
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
  
  
$(document).ready(function(){
$("#go_smsbundle_abonnementtype_client").on(
    {
        keyup:function(e)
        {
            $('#details_client').hide();
            var tel=$(this).val();
            var len_tel=tel.length;
            if(len_tel==9)
            {
                if(isValideTelephone(tel))
                {
                    $.get('details_client-'+tel+'.golob', '', function(reponse){
                        $('#details_client').show();
                        $('#details_client .panel-body').html(reponse);
                    });
                  $("#details_client").show();

                    
    }else{
    show_alert("Erreur: le numéro du téléphone entré n'est pas valide!");

    }
    }
    else
    {

    }
}
});
//================ENd of ready
});

