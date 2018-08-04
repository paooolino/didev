
(function() {
  
  var invalidHandler = function(event, validator) {
    // 'this' refers to the form
    var errors = validator.numberOfInvalids();
    if (errors) {
      $(this).find('.row.error').show();
    } else {
      $(this).find('.row.error').hide();
    }
  };
  
  var showErrors = function(errorMap, errorList) {
    $('small.error').hide();
    for (var i = 0; i < errorList.length; i++) {
      $(errorList[i].element).closest('.input_wrapper').find('small.error').css({display: 'block'});
    }
  };
  
  $('#new_form_onlist').validate({
    invalidHandler: invalidHandler,
    showErrors: showErrors,
    onfocusout: false,
    onkeyup: false,
    onclick: false
  });
  
  $('#new_form_location_suggest').validate({
    invalidHandler: invalidHandler,
    showErrors: showErrors,
    onfocusout: false,
    onkeyup: false,
    onclick: false
  });  
  
  $('#new_form_party').validate({
    invalidHandler: invalidHandler,
    showErrors: showErrors,
    onfocusout: false,
    onkeyup: false,
    onclick: false
  });  
  
  $('#new_form_contact').validate({
    invalidHandler: invalidHandler,
    showErrors: showErrors,
    onfocusout: false,
    onkeyup: false,
    onclick: false
  });
  
})();
