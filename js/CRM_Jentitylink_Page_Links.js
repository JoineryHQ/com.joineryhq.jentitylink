CRM.$(function($) {
  
  var setLinkInspector = function setLinkInspector() {
    var setValue = $(this).prop('checked');
    var userCid = CRM.vars.jentitylink.userCid;
    
    CRM.api4('Setting', 'set', {
      values: {"jentitylink_enable_inspector":setValue},
      contactId: userCid
    }).then(function(results) {
      updateView(results[0].value);
    }, function(failure) {
      console.log(failure);
    });
  };
  
  var updateView = function updateView(status) {
    var intStatus = (1 * status);
    // Make sure the checkbox state is synced to actual live value.
    $('input#jentitylink-inspector-set-value').prop('checked', status);
    // Update text and class of text indicator in accordion header.
    $('#jentitylink-inspector-status-label').addClass('status-' + intStatus);
    $('#jentitylink-inspector-status-label').removeClass('status-' + (1 * !status));    
    var texts = {
     0: ts('Off'),
     1: ts('On'),
    }
    $('#jentitylink-inspector-status-label').html(texts[intStatus]);
    console.log('texts', texts);
    console.log('status', status);
    console.log('intStatus', intStatus);
    console.log('text', texts[status]);
  }
  
  $('input#jentitylink-inspector-set-value').click(setLinkInspector);
  updateView($('input#jentitylink-inspector-set-value').prop('checked'));
});
