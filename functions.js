//check email
function checkEmail(str1,str2){
	var value = document.getElementById(str1).value;
	if(!isEmail(value)){
		document.getElementById(str2).style.display = "block";
		return false;
	}
	else
		document.getElementById(str2).style.display = "none";
	return true;
}

function isEmail(s){
	var patrn=/^\w+([-+.]\w+)*@\w+([-.]\\w+)*\.\w+([-.]\w+)*$/;
	if (!patrn.exec(s)) 
		return false;
	return true;
}
//check username        
function checkUsername(str1,str2){
	var value = document.getElementById(str1).value;
	if(value.length<3||value.length>15){
		document.getElementById(str2).style.display = "block";
		return false;
	}
	else
		document.getElementById(str2).style.display = "none";
	return true;
}



//check password
function checkPassword(str1,str2){
	var value = document.getElementById(str1).value;
	if(value.length < 6||value.length > 15){
		document.getElementById(str2).style.display = "block";
		return false;
	}
	else
		document.getElementById(str2).style.display = "none";
	return true;
}

//double check password
function checkRePwd(str1,str2){
	var value = document.getElementById(str1).value;
	var password = document.getElementById("password").value;
	if(value!= password){
		document.getElementById(str2).style.display = "block";
		return false;
	}
	else
		document.getElementById(str2).style.display = "none";
	return true;
}


function checkAll(){
	if(checkEmail('email','divright1')&&checkUsername('username','divright2')&&checkPassword('password','divright3')&&checkRePwd('repwd','divright4')){
	    return true;	
        //location = "reg_success.html";	
	}
	return false;	
}


function checkAll2(){   
	if(checkEmail('email','divright1')&&checkPassword('password','divright2')){
	    return true;	
        //location = "reg_success.html";	
	}
	return false;	
}

//upload
function checkNotnull(str1,str2){
	var value = document.getElementById(str1).value;
	if(value.length<1){
		document.getElementById(str2).style.display = "block";
		return false;
	}
	else
		document.getElementById(str2).style.display = "none";
	return true;
}
function checkPicture(str1,str2){
	var value = document.getElementById(str1).value;
	if(value.length==""){
		document.getElementById(str2).style.display = "block";
		return false;
	}
	else
		document.getElementById(str2).style.display = "none";
	return true;
}



function checkAll3(){   
	if(checkNotnull('rname','divright1')&&checkNotnull('ingredient','divright4')&&checkNotnull('instruction','divright5')&&checkPicture('picture','divright6')){
	    return true;	
        //location = "reg_success.html";	
	}
	return false;	
}

//add fuction
var i=2;
function addInstruction(){
       var addin = document.getElementById('instructionarea'); 
       var button = document.getElementById('add2');
       var stepp = document.createElement('div');
       stepp.innerHTML='Step '+ i +':';       
       addin.insertBefore(stepp,button);
       var newdiv = document.createElement('div');
       addin.insertBefore(newdiv,button);
       newdiv.setAttribute('class','formInput1');
       var textarea = document.createElement('textarea');
       textarea.setAttribute('rows','2');
       textarea.setAttribute('cols','25');
       textarea.setAttribute('name','instruction_'+i);
       newdiv.appendChild(textarea);
       i++;
}

var j=3;
function addIngredient(){
    var ul=document.getElementById('inlist');
    var newli =document.createElement('li');
    ul.appendChild(newli);
    var newinput =document.createElement('input');
    newli.appendChild(newinput);
    newinput.setAttribute('name','ingredient_'+i);
    newinput.setAttribute('type','text');
    j++;
}

