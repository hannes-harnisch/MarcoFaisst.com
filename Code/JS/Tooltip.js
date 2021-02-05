$(document).ready(function()
{
	setTooltipAnchor("E", "Einzelausstellung");
	setTooltipAnchor("G", "Gruppenausstellung");
	setTooltipAnchor("K", "Katalog");
	$("[data-toggle='tooltip']").tooltip();
});

function setTooltipAnchor(type, content)
{
	$(`.tooltip-${type}`).replaceWith(`<a data-toggle='tooltip' title='${content}'>(${type})</a>`);
}