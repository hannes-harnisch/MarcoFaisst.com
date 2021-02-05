$(document).ready(function()
{
	$("form").validate(
	{
		submitHandler: processForm
	});
});

function processForm()
{
	$("#form-feedback").empty();
	if($("#name").val() === "" || $("#message").val() === "")
		warnAboutEmptyFields();
	else
		checkEmailAndProceed();
	return false;
}

function warnAboutEmptyFields()
{
	$("#form-feedback").html(`

		<i class='fa fa-exclamation-circle'></i>
		Bitte lassen Sie keine Felder frei.

	`);
}

function checkEmailAndProceed()
{
	var regex = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
	if(regex.test($("#email").val()))
		postToContactFormScript();
	else
		warnAboutInvalidEmailAddress();
}

function postToContactFormScript()
{
	$("#submit").html("<i class='fa fa-spinner fa-pulse'></i>");
	$.post("/Code/ContactForm.php", $("form").serialize(), function(successful)
	{
		if(successful)
			showSuccessMessage();
		else
			showFailureMessage();
	});
}

function showSuccessMessage()
{
	$("form").css("display", "none");
	$("#form-feedback").html(`

		<span id='form-success'>
			<i class='fa fa-check-square-o'></i>
			Nachricht erfolgreich verschickt. Eine Kopie wurde an die angegebene E-Mail-Adresse gesendet.
		</span>

	`);
}

function showFailureMessage()
{
	$("#submit").html("Nachricht versenden");
	$("#form-feedback").html(`

		<i class='fa fa-exclamation-triangle'></i>
		Es ist ein Fehler aufgetreten.

	`);
}

function warnAboutInvalidEmailAddress()
{
	$("#form-feedback").html(`

		<i class='fa fa-exclamation-circle'></i>
		Das ist keine gültige E-Mail-Adresse.

	`);
}