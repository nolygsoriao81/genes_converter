
 jQuery(document).ready(function() {
    jQuery('#cropContaineroutput').change(function() {
         var nonce_data = jQuery(this).attr("data-nonce")
         var pictureInput = jQuery('#cropContaineroutput').prop('files')[0];
         var fileCollector = new FormData();
         fileCollector.append('file', pictureInput);
         fileCollector.append('action', "load_generated_genes");
         fileCollector.append('nonce', nonce_data);
         jQuery(".progress-bar-genes").text('0%');
         jQuery(".progress-bar-genes").css('width', '0%');
         jQuery("#generated-table").html("");
         jQuery.ajax({
             url: ajaxAdmin.ajaxurl,
             type: 'POST',
             processData: false, // important
             contentType: false, // important 
             dataType: 'html',
             data: fileCollector,
             success: function(jsonData) {
                  jQuery("#cropContaineroutput").show();
                  jQuery(".progress-genes").hide();
                  jQuery("#generated-table-genes").html(jsonData);
             },
             error: function(xhr, ajaxOptions, thrownError) {
                 alert(xhr.status);
                 alert(xhr.responseText);
                 alert(thrownError);
             },
             xhr: function () {
                 jQuery("#cropContaineroutput").hide();
                 jQuery(".progress-genes").show();
                  var xhr = new window.XMLHttpRequest();
                  xhr.upload.addEventListener("progress", function (evt) {
                     if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        jQuery(".progress-bar-genes").text(percentComplete + '%');
                        jQuery(".progress-bar-genes").css('width', percentComplete + '%');
                     }
                  }, false);
                  return xhr;
            },
         });
     });
 });