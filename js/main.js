/**
 * Application JS
 */
(function() {

  var form = new ReptileForm('form');

  // Do something after validation is successful, but before the form submits.
  form.on('beforeSubmit', function() {
    $('form [name="user"]').remove();
  });

  // Do something when the AJAX request has returned in success
  form.on('xhrSuccess', function(e, data) {
    if (data.redirect) {
      location.href = data.redirect;
    }
  });

  // Do something when the AJAX request has returned with an error
  form.on('xhrError', function(e, xhr, settings, thrownError) {
  });

})();
