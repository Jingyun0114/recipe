
function xmlReq() {
    //code
    var xhttp;
    if (window.XMLHttpRequest) {
      xhttp = new XMLHttpRequest();
   } else {
      // for IE 5, IE 6
      xhttp = new ActiveXObj("Microsoft.XMLHTTP");
   }
   return xhttp;
}

function appendNotice(text) {
    var notice = document.createElement("span");
    var noticeText = document.createTextNode(text);
    notice.appendChild(noticeText);
    
    var comment = document.getElementById("comment");
    var allComm = document.getElementById("all_comm");
    comment.insertBefore(notice, allComm);
}

function appendNewComm(name, text) {
    var tr = document.createElement("tr");
    var tdName = document.createElement("td");
    tdName.appendChild(document.createTextNode(name + ":"));
    var tdText = document.createElement("td");
    tdText.appendChild(document.createTextNode(text));
    tr.appendChild(tdName);
    tr.appendChild(tdText);
    
    var commTable = document.getElementById("all_comm").getElementsByTagName("table")[0];
    commTbody = commTable.getElementsByTagName("tbody")[0];
    //commTbody.appendChild(tr);
    commTbody.insertBefore(tr, commTbody.childNodes[0]);
    //alert(commTbody);
    
}

function addComment(rid, uid) {
    //var oldNotice = null;
    oldNotice = document.getElementById("comment").getElementsByTagName("span");
    //alert(oldNotice);
    //if (!(oldNotice === undefined)) {
    //   oldNotice.parentNode.removeChild(oldNotice); 
    //}
    //if (typeof(oldNotice) != 'undefined' && oldNotice != null)
    //{
    //    alert(oldNotice);
    //    oldNotice.parentNode.removeChild(oldNotice);
    //}
    if (oldNotice.length)
    {
        var oldSpan = oldNotice[0];
        //var spanParent = document.getElementById("comment");
        //alert(oldNotice[0].parentNode);
        oldSpan.parentNode.removeChild(oldSpan);
        //spanParent.removeChild(oldSpan);
    }
    
    var comText = document.getElementById("com_context").value;
    if (comText == null || comText == "") {
        var text = "Sorry, please add comment first."; 
        appendNotice(text);
    } else if (uid == 0) {
        var text = "Sorry, please login first."; 
        appendNotice(text);
   } else {
        var xhttp = xmlReq();

        xhttp.onreadystatechange = function () {
           if (xhttp.readyState == 4 && xhttp.status == 200) {
             //alert(xhttp.responseText);
             var mark = xhttp.responseText.split(":")[0];
             var userName = xhttp.responseText.split(":")[1];
             var newComText = xhttp.responseText.split(":")[2];
             
             if (mark == 0) {
                 text = newComText;
                 appendNotice(text);
             } else {
                //alert(userName);
                //alert(newComText);
                 appendNewComm(userName, newComText);
             }
           }
        }
        
        xhttp.open("GET", "add_comment.php?ct="+comText+"&uid=" + uid + "&rid=" + rid, true);
        xhttp.send();
   }
   
   
}