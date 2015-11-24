/***********************************************
* Alicia Broederdorf
* November 14, 2015
* Script to display tab content
***********************************************/

//Source: http://stackoverflow.com/questions/19074171/how-to-toggle-a-divs-visibility-by-using-a-button-click
function toggle(id){
	var div = document.getElementById(id);
    div.style.display = div.style.display == "none" ? "block" : "none";
}

function showSearch(){
	document.getElementById("searchDiv").style.display = "block";
	document.getElementById("addDiv").style.display = "none";
	document.getElementById("searchButton").style.backgroundColor = "silver";
	document.getElementById("addButton").style.backgroundColor = "white";
}

function showAdd(){
	document.getElementById("searchDiv").style.display = "none";
	document.getElementById("addDiv").style.display = "block";
	document.getElementById("searchButton").style.backgroundColor = "white";
	document.getElementById("addButton").style.backgroundColor = "silver";
}

//Create event listeners
document.getElementById("searchButton").addEventListener("click", showSearch);
document.getElementById("addButton").addEventListener("click", showAdd);

//Initialize
document.getElementById("searchDiv").style.display = "block";
document.getElementById("searchButton").style.backgroundColor = "silver";
document.getElementById("addDiv").style.display = "none";

//Hide all data tables
document.getElementById("rtClTblDiv").style.display = "none";
document.getElementById("peopleTblDiv").style.display = "none";
document.getElementById("mtnTblDiv").style.display = "none";
document.getElementById("routeTblDiv").style.display = "none";
document.getElementById("skillTblDiv").style.display = "none";
document.getElementById("cliSkTblDiv").style.display = "none";
document.getElementById("rtSkTblDiv").style.display = "none";
