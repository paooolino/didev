
App.chosen = {
  removeEvents: function() {
    $('select').chosen('destroy');
  },
  attachEvents: function() {
    $('select').chosen();
  }
};
