$(document).ready(function()
{
    $("a:contains('(E)')").replaceWith("<a data-toggle='tooltip' title='Einzelausstellung'>(E)</a>");
    $("a:contains('(G)')").replaceWith("<a data-toggle='tooltip' title='Gruppenausstellung'>(G)</a>");
    $("a:contains('(K)')").replaceWith("<a data-toggle='tooltip' title='Katalog'>(K)</a>");
    $("[data-toggle='tooltip']").tooltip(); 
});