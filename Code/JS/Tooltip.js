$(document).ready(function()
{
	$(".tooltip-E").replaceWith("<a data-toggle='tooltip' title='Einzelausstellung'>(E)</a>");
    $(".tooltip-G").replaceWith("<a data-toggle='tooltip' title='Gruppenausstellung'>(G)</a>");
    $(".tooltip-K").replaceWith("<a data-toggle='tooltip' title='Katalog'>(K)</a>");
    $("[data-toggle='tooltip']").tooltip(); 
});