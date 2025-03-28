function setfocus() {
    if (document.login.uid.value=="") {
          document.login.uid.focus();
    } else {
          document.login.passw.focus();
    }
}

function confirminput(myform) {
    if (myform.uid.value.length && myform.passw.value.length) {
      return (true);
    } else if (!(myform.uid.value.length)) {
      myform.reset();
      myform.uid.focus();
      alert ("You must enter a valid username");
      return (false);
    } else {
      myform.passw.focus();
      alert ("You must enter a valid password");
      return (false);
    }
}
window.onload = setfocus;