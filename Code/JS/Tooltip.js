$(document).ready(function()
{
	$(".tooltip").attr("data-toggle", "tooltip");

	$(".info-e").attr("title", "Einzelausstellung");
	$(".info-e").text("(E)");

	$(".info-g").attr("title", "Gruppenausstellung");
	$(".info-g").text("(G)");

	$(".info-k").attr("title", "Katalog");
	$(".info-k").text("(K)");

	$("[data-toggle='tooltip']").tooltip(); 
});