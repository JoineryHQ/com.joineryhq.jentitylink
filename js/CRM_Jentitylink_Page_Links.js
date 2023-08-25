CRM.$(function($) {
  if (CRM.vars.jentitylink.inspectorEnabledNow) {
    var t = 500; CRM.$('a.jentitylink-inspection-link').fadeOut(t).fadeIn(t).fadeOut(t).fadeIn(t);
    CRM.vars.jentitylink.inspectorEnabledNow = false;
  }
});

