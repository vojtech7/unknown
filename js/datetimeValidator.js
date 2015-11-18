Nette.validators.datetimeType = function(elem, arg, val) {
  var currVal = val;
  if(currVal == '')
    return true;    //hodnota ve formulari neni povinna
  
  // var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
  var rxDatePattern = /^(\d{2})\.(\d{2})\.(\d{4}) (\d{2}):(\d{2})$/; //Declare Regex
  var dtArray = currVal.match(rxDatePattern); // is format OK?
  
  if(dtArray == null) 
    return false;

  //Checks for dd.mm.yyyy hh:mm format.
  day   = Number(dtArray[1]);
  month = Number(dtArray[2]);
  year  = Number(dtArray[3]);
  hour  = Number(dtArray[4]);
  min   = Number(dtArray[5]);

  if(month < 1 || month > 12)
    return false;
  else if(day < 1 || day> 31)
    return false;
  else if((month==4 || month==6 || month==9 || month==11) && day ==31)
    return false;
  else if(month == 2) {
    var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
    if(day> 29 || (day ==29 && !isleap))
      return false;
  }

  if(hour < 0 || hour > 23)
    return false;
  else if(min < 0 || min > 59)
    return false;

  return true;
}