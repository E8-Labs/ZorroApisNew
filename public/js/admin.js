var subDomain = "http://localhost:8000/";
//var subDomain = "https://daakin.com/bookswap/";

$(document).ready(function(){

 //alert($('#mytextarea'));

  // $('#mytextarea').ckeditor();
        // $('.textarea').ckeditor(); // if class is prefered.
    setNavSelection();

   $('#updateBtn').click(function(event){
      
       var value = $("#mytextarea").val().trim();
        var method_name = jQuery(this).attr("method_name");
         updateField(method_name,value);
    });
   $('#updateSettingsBtn').click(function(event){
       updateSetting();
    });

    $('#startNewContestBtn').click(function(event){
      createNewContest();
    });

    $('.remove-admin').click(function(event){

      var userId = jQuery(this).attr("data-user-id");
      var viewId = jQuery(this).attr("id");
      removeUser(userId, viewId );
      // viewId is for disable button
    });



   // init();

   /// alert(100);
    
  });


function updateSetting(){

 

  var styleEn = "border:#FF0000 1px solid;";
  var styleDis = "border:#ddd 1px solid;";

  var api_key = $("#api_key").val().trim();
  var points = $("#points").val().trim();
  var tickets = $("#tickets").val().trim();
  var prize = $("#prize").val().trim();
  var contact_us_to = $("#contact_us_to").val().trim();
  // var contact_us_cc = $("#contact_us_cc").val().trim();
  var payout_to = $("#payout_to").val().trim();
  // var payout_cc = $("#payout_cc").val().trim();


    if(!api_key){
      alert("Fill Api Key");
      $('#api_key').attr('style', styleEn);return;
    }else{
      $('#api_key').attr('style', styleDis);
    }

    if(!points){
      alert("Fill Point");
      $('#points').attr('style', styleEn);return;
    }else{
      $('#points').attr('style', styleDis);
    }

    if(!tickets){
      alert("Fill Tickets");
      $('#tickets').attr('style', styleEn);return;
    }else{
      $('#tickets').attr('style', styleDis);
    }

    if(!prize){
      alert("Fill Prize");
      $('#prize').attr('style', styleEn);return;
    }else{
      $('#prize').attr('style', styleDis);
    }

    if(!contact_us_to){
      alert("Fill Contact Us To");
      $('#contact_us_to').attr('style', styleEn);return;
    }else{
      $('#contact_us_to').attr('style', styleDis);
    }

    // if(!contact_us_cc){
    //   alert("Fill Contact Us CC");
    //   $('#contact_us_cc').attr('style', styleEn);return;
    // }else{
    //   $('#contact_us_cc').attr('style', styleDis);
    // }

     if(!payout_to){
      alert("Fill Payout To");
      $('#payout_to').attr('style', styleEn);return;
    }else{
      $('#payout_to').attr('style', styleDis);
    }

    // if(!payout_cc){
    //   alert("Fill Payout CC");
    //   $('#payout_cc').attr('style', styleEn);return;
    // }else{
    //   $('#payout_cc').attr('style', styleDis);
    // }

  
   $('#updateSettingsBtn').html("Updating....");
  $('#updateSettingsBtn').attr("disabled", true);

   var dataMain = { 
                api_key:api_key,
                points:points,
                tickets:tickets,
                prize:prize,
                contact_us_to:contact_us_to,
                // contact_us_cc:contact_us_cc,
                payout_to:payout_to,
                // payout_cc:payout_cc,
           };
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
      $.ajax({
        type: "post",
       // contentType: false, // for image upload
            //   processData: false, // for image upload 
               dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: dataMain,
        url: subDomain + '/settings_save',

        success: function( data){
          $('#updateSettingsBtn').html("Update");
          $('#updateSettingsBtn').attr("disabled", false);
          console.log(data);
          if( data.success == false  ){
            $message = ""
            for(var i=0; i < Object.keys(data.error).length ; i++)
            {
              $message += Object.values(data.error)[i] + "  ";
            }
              alert( $message);
          }else{
            alert( data.error);
          }
        },
        error: function (data, textStatus, errorThrown) {
          $('#updateSettingsBtn').html("Update");
          $('#updateSettingsBtn').attr("disabled", false);
          console.log(data);
          alert( "error:  " + data.toString());
           }
      });
}


function updateField(method_name,value){

	$('#updateBtn').html("Updating....");
	$('#updateBtn').attr("disabled", true);
   var dataMain = { 
                field:value
           };
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
      $.ajax({
        type: "post",
       // contentType: false, // for image upload
            //   processData: false, // for image upload 
               dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: dataMain,
        url: subDomain + '/' + method_name,

        success: function( data){
        	$('#updateBtn').html("Update");
        	$('#updateBtn').attr("disabled", false);
          console.log(data);
          if( data.success == false  ){
            $message = ""
            for(var i=0; i < Object.keys(data.error).length ; i++)
            {
              $message += Object.values(data.error)[i] + "  ";
            }
              alert( $message);
          }else{
            alert( data.error);
          }
        },
        error: function (data, textStatus, errorThrown) {
        	$('#updateBtn').html("Update");
        	$('#updateBtn').attr("disabled", false);
          console.log(data);
          alert( "error:  " + data.toString());
           }
      });
}

function createNewContest(){

   var startDate = new Date($('#start_data').val());
   var endDate = new Date($('#end_data').val());

   if( endDate <= startDate ){
        alert("End Date must be greater then start date");
    return;
   }

  $('#startNewContestBtn').html("Updating....");
  $('#startNewContestBtn').attr("disabled", true);

   var dataMain = { 
                start_month: 1 + startDate.getMonth(),
                start_year:startDate.getFullYear(),
                end_month: 1 + endDate.getMonth(),
                end_year:endDate.getFullYear(),
                start_date: $('#start_data').val(),
                end_date: $('#end_data').val(),

           };
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
      $.ajax({
        type: "post",
       // contentType: false, // for image upload
            //   processData: false, // for image upload 
               dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: dataMain,
        url: subDomain + '/create_contest',

        success: function( data){
          $('#startNewContestBtn').html("Start New Contest");
          $('#startNewContestBtn').attr("disabled", false);
          console.log(data);
          if( data.success == false  ){
             //alert( data.error);
            var message = "";
            for(var i=0; i < Object.keys(data.error).length ; i++)
            {
              message += Object.values(data.error)[i] + "  ";
            }
              alert( message);
          }else{
            alert( data.message);
          }
        },
        error: function (data, textStatus, errorThrown) {
          $('#startNewContestBtn').html("Start New Contest");
          $('#startNewContestBtn').attr("disabled", false);
          console.log(data);
          alert( "error:  " + data.toString());
           }
      });
}
function removeUser(id , viewId){


  $("#" + viewId).html("Removing....");
  $("#" + viewId).attr("disabled", true);

   var dataMain = { 
                user_id: id

           };
    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
      $.ajax({
        type: "post",
       // contentType: false, // for image upload
            //   processData: false, // for image upload 
               dataType: "json",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: dataMain,
        url: subDomain + '/remove_admin',

        success: function( data){
          $("#" + viewId).html("Remove");
          $("#" + viewId).attr("disabled", false);
          console.log(data);
          if( data.success == false  ){
             //alert( data.error);
            var message = "";
            for(var i=0; i < Object.keys(data.message).length ; i++)
            {
              message += Object.values(data.message)[i] + "  ";
            }
              alert( message);
          }else{
            location.reload();
            //alert( data.message);
          }
        },
        error: function (data, textStatus, errorThrown) {
         $("#" + viewId).html("Remove");
         $("#" + viewId).attr("disabled", false);
          console.log(data);
          alert( "error:  " + data.toString());
           }
      });
}