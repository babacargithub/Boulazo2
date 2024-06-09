function Update_etat_dep()
{
    //$.get('etats_depart.golob',"", function(res){ $("#menu").html(res); });
  var url=window.location.href ;
$.ajax({
    url: 'etats_depart.golob',
    success: function(res){
        $(".etat_departs li.nav-header").siblings().remove();
        $(".etat_departs li.nav-header").after(res);
    }, 
    global: false,     // this makes sure ajaxStart is not triggered
    dataType: 'html'
});
}	
//==========Afficher la liste des inscrits par appel AJAX===================
     $('body').on('click','.liste_inscrit, .liste_depart, .liste_chiffre', function(e){
         e.preventDefault();
          $.get($(this).attr('href'),'', function(reponse){
             $('#content_body').html(reponse);});
     
     });
     //====================Traiter de manière uniformisée les boutons d'action dans les listes==========
   
     $(document).ajaxStart(function()
						{
							showLoader();	
						}
				);
				$(document).ajaxComplete(function()
						{
								hideLoader();
						}
				);
$(document).ready(function()
{
 //=============================MODULE RESERVATION=============================================================
 var caravane_bundle="go_caravanebundle";
    var module_form=caravane_bundle+"_reservationtype";

 $("#"+module_form+"_client_prenom, #"+module_form+"_client_nom").attr('required',false);
 $("#"+module_form+"_client_prenom_div,"+"#"+module_form+"_client_nom_div").hide();
 $('#details_client').hide();
 
 //===================Traitement lors de la selection d'un départ====================
 $("#"+module_form+"_depart").change(function(e)
        { 
                var trajet=$(this,"option:selected").val();
                var trajet_dep=$(this).find('option:selected').attr('trajet');
                var dep_ugb_dk=1;
                var dep_dk_ugb=2;
                if(trajet_dep==dep_ugb_dk)
                {
                $("#"+module_form+"_pointDep").find('option[trajet='+dep_ugb_dk+']').show();
                $("#"+module_form+"_pointDep").find('option[trajet='+dep_dk_ugb+']').hide();
                $("#"+module_form+"_des").find('option[trajet='+dep_ugb_dk+']').show();
                $("#"+module_form+"_des").find('option[trajet='+dep_dk_ugb+']').hide();
                }else
                {
                $("#"+module_form+"_pointDep").find('option[trajet='+dep_dk_ugb+']').show();
                $("#"+module_form+"_pointDep").find('option[trajet='+dep_ugb_dk+']').hide();
                $("#"+module_form+"_des").find('option[trajet='+dep_dk_ugb+']').show();
                $("#"+module_form+"_des").find('option[trajet='+dep_ugb_dk+']').hide();
                
                }
                if(trajet_dep==2)
                {
                 $("#"+module_form+"_paye").attr('required',false);
                 $("#"+module_form+"_paye_0").attr('required',false);
                 $("#"+module_form+"_paye_1").attr('required',false);
                 $("#"+module_form+"_paye_div").hide();
                }
                else if(trajet_dep==1)
                {
                 $("#"+module_form+"_paye").attr('required',true);
                 $("#"+module_form+"_paye_div").show();
                }
			}
			);
   //==================traitement lors de la saisie du numéro de téléphone
 $("#"+module_form+"_client_tel").on(
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
                    $("#"+module_form+"_client_prenom_div,"+"#"+module_form+"_client_nom_div").show();

                    /*function(result, data1, xch)
            {
        reponse=JSON.parse(result);
    if(reponse[0].client_exist==1)
        {
        $("#formulaire_detail_client").show();
        $("#div_prenom_res").hide();	
        $("#div_nom_res").hide();	
        $("#nom_res").attr("required", false);
        $("#prenom_res").attr("required", false);
        $("#prenom_detail").html(reponse[0].prenom_client);
        $("#nom_detail").html(reponse[0].nom_client);
        $("#num_voy_detail").html(reponse[0].nombre_voyage);
        $("#voy_en_cours_detail").html(reponse[0].voyage_en_cours);
         if(reponse[0].voyage_en_cours!="")
         {
                 alert("Ce client a fait une réservation sur le  départ  "+reponse[0].voyage_en_cours);

//$("#formulaire_reservation :input").prop("disabled", true); 
         }


}
else
{
$("#formulaire_detail_client").hide();
$("#div_prenom_res").show();
$("#prenom_res").attr("required", true);	
$("#div_nom_res").show();	
$("#nom_res").show().attr("required", true);	
}
}*/
    }else{
    show_alert("Erreur: le numéro du téléphone entré n'est pas valide!");

    }
    }
    else
    {

    }
}
});

//_____ actualisation des états des départs toutes les 5 minutes--------------------->>>		
setInterval('Update_etat_dep()', 50000);	


//========== GESTION DE LA BARRE DE RECHERCHE DU CLIENT A COTE GAUCHE DE LA PAGE =================//
$('#rechercheHistoVoyage').submit(function(e)
{
    e.preventDefault();
    var tel=$("#rechercheHisto").val();
    $.get("historique_voyage_client-"+tel+".golob", "", 
    function(res){ $("#modal").html(res).
                dialog({
                        width: 800, 
                        dialogClass: "alert",
                        title: "Détails du client et historique voyage"
                    }
                    );
            });
}
 );
 //================+ACTION POUR GERER LA LIGNE DE COMMANDE A COTE GAUCHE
 $("#command-lineForm").on('submit',function(e){
    e.preventDefault();
    var acceptedCommands=new Array('p','d','m');
   var tel=$('#commandeField').val();
    var targetUrl='voyage_en_cours-'+tel+'.golob';
    //if(/^[0-9]{0,}-[pmds]([\-0-9]{2,3}){0,1}$/.test(data))
    if(isValideTelephone(tel))
    {
     $.get(targetUrl,'', function(reponse){
               $("#modal").html(reponse).dialog({
                        width: 900, 
                        dialogClass: "alert",
                        title: "Détails du dernier voyage"
                    }); 
           });
     
        }else
        {
            alert('Téléphone invalide!');
        }
   
});

});
