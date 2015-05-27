<script
function validateForm()
{
var x=document.forms["confirmOrder"]["givenFirstName"].value;
if (x==null || x=="")
  {
  alert("First name must be filled out");
  return false;
  }
 
 var x=document.forms["confirmOrder"]["givenLastName"].value;
if (x==null || x=="")
  {
  alert("Last name must be filled out");
  return false;
  }
 
var x=document.forms["confirmOrder"]["givenEmai"].value;
if (x==null || x=="")
  {
  alert("Email must be filled out");
  return false;
  }
 
var x=document.forms["confirmOrder"]["givenTelephone"].value;
if (x==null || x=="")
  {
  alert("Phone number must be filled out");
  return false;
  } 
var x=document.forms["confirmOrder"]["givenAddress"].value;
if (x==null || x=="")
  {
  alert("Address must be filled out");
  return false;
  }
 var x=document.forms["confirmOrder"]["givenCity"].value;
if (x==null || x=="")
  {
  alert("City must be filled out");
  return false;
  } 
var x=document.forms["confirmOrder"]["givenPostCode"].value;
if (x==null || x=="")
  {
  alert("Postcode must be filled out");
  return false;
  }  
} 

</script>