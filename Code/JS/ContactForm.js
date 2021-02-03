$(document).ready(function()
{
	$('contact-form').validate(
	{
		submitHandler: function()
		{
			$('#result-feedback').empty();
			if($('#name').val() === '' || $('#message').val() === '')
				$('#result-feedback').html(`

					<i class="fa fa-exclamation-circle"></i>
					Bitte lassen Sie keine Felder frei.

				`);
			else
			{
				var regex = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
				if(regex.test($('#email').val()))
				{
					$('#submit').html('<i class="fa fa-spinner fa-pulse"></i>');
					$.post('/Code/ContactForm.php', $('contact-form').serialize(), function(successful)
					{
						if(successful)
						{
							$('contact-form').css('display', 'none');
							$('#result-feedback').html(`

								<span style="color:var(--text)">
									<i class="fa fa-check-square-o"></i>
									Nachricht erfolgreich verschickt. Eine Kopie wurde an die angegebene E-Mail-Adresse gesendet.
								</span>

							`);
						}
						else
						{
							$('#submit').html('Nachricht versenden');
							$('#result-feedback').html(`

								<i class="fa fa-exclamation-triangle"></i>
								Es ist ein Fehler aufgetreten.

							`);
						}
					});
				}
				else
					$('#result-feedback').html(`

						<i class="fa fa-exclamation-circle"></i>
						Das ist keine gültige E-Mail-Adresse.

					`);
			}
			return false;
		}
	});
});