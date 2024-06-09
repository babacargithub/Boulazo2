 var GET="GET";
 var POST="POST";

function loadView(href,donnees, id_content, method=null)
{

$.ajax(
{
        url: href,
        type: method,
        data: donnees,
        dataType: "HTML",
        success: function(data, res){

                $('#'+id_content).html(data);

        },
        error: function(){
        alert("Erreur! Impossible de charger le contenu "+href);
        }

    }
 );	

}
//============================== TABLE FUNCTIONS=================================================
function removeTableRow(id_table, id_row)
{
	$("#"+id_table+" #"+id_row).remove();
}
function updateTableRow(source, id_table, id_row)
{
	$("#"+id_table+" #"+id_row).html(source);
}



//===============================AJAX FUNCTIONS===================================================
function showAlert(code_ajax, duree_alert=null)
{
    var duree=1000;
    if(duree_alert!=null)
    {
       duree=duree_alert;
    }
   try{
       //var response=JSON.parse(code_ajax);
       var response=$.parseJSON(code_ajax);
        
        if(response.code==0)
        {
        $('#error_msg').show().html(response.message).dialog({title: "Une erreur s'est produite!"}).prev(".ui-dialog-titlebar").css("background","red");;
        setTimeout(function()
                    {$('#error_msg').hide().dialog('close');
                    }, 10000);
        }
        else if(response.code==1)
        {	
        $('#success_msg').show().html(response.message).dialog({title:"Réponse"}).prev(".ui-dialog-titlebar").css("background","green");;
        setTimeout(function()
        {$('#success_msg').hide().dialog('close');
        }, 10000
        );
        }
        return response;
    }
        catch(e){ 
           
                $('#success_msg').show().html(code_ajax).dialog();  
            }
        
          
}

function showLoader()
{
    $('#loader').show();
}
function hideLoader()
{
    $('#loader').hide();
}
function resetForm(id_form)
{
	document.getElementById(id_form).reset();
}
function refreshPortion(url, prt_id)
{
	$.get(url, function(dt){$("#"+prt_id).html(dt);});
}

function linkToAjaxRequest(event, href)
{
    event.preventDefault(); 
    
    $.get(href, $(this).attr('data'),function(response){showAlert(response)});
   
}
function isValideTelephone(tel)
{
  if(/^7[7680]{1}[0-9]{7}$/.test(tel))
  {
  return true;
  }
  else{
      return false;
  }
}
     
 //============ Envoie par AJAX de tous les formulaires des entités ==========
 $('body').on('submit','[id$="bundle"], [id$="type"]', function(e){
         e.preventDefault();
         var id_form=$(this).attr('id');
         var request=$.post($(this).attr('action'), $(this).serialize()).done(function(res){
             var rep=showAlert(res, 2000);
            if(res.code==1)
             {
                 resetForm(id_form);
             }
        }).fail(function(res,textStatus,error){alert(error + " "+res.responseText+ " Une erreur s'est produite, la requette n'est pas envoyée");});
     });
//============== Gérer les bouttons pour les actions "supprimer", "modifier", "supprimer"
  $('body').on('click', '.confirmer_action, .payer_action, .annuler_action, .depart_action, .update_action', function(e){
         e.preventDefault();
         var action_class=$(this).attr('class');
         var id=$(this).attr('id');
         var confirmAction=true;
         if(action_class!="update_action" && action_class!="payer_action")
         {
            var confirmAction=confirm("Voulez vraiment "+action_class.substring(0, action_class.length-7)+" cet élément?");
         }
         if(confirmAction==true)
         {
          $.get($(this).attr('href'),'', function(reponse){
              if(action_class=="update_action")
              {
                 $("#modal").html(reponse).dialog(); 
              }else{
           showAlert(reponse);
           // this function's job is to delete the row after successfull deletion in the background 
           
            if(action_class=="annuler_action"||action_class=="supprimer_action")
            {
                $('#'+id).closest('tr').remove();
               
            }
                }});
             
            if(action_class=="confirmer_action")
            {
                $('td:first', '#row-'+id).html('<strong>Déjà Confirmé</strong>');
            }
        }
       
     
     });
