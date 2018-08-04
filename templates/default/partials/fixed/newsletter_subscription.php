<section class="sheet">
<div class="contentx">
<div class="form-wrapper top-form-enabled">
<form validate="true" class="simple_form contact-form" novalidate="novalidate" id="new_form_newsletter_subscription" enctype="multipart/form-data" action="/form/newsletter" accept-charset="UTF-8" data-remote="true" method="post"><input name="utf8" type="hidden" value="&#x2713;" /><div class="row">
<div class="large-6 columns">
<div class="input_wrapper string required form_newsletter_subscription_name"><label class="string required control-label" for="form_newsletter_subscription_name"><abbr title="campo obbligatorio">*</abbr> nome</label><input size="20" maxlength="50" class="string required" type="text" name="form_newsletter_subscription[name]" id="form_newsletter_subscription_name" /></div>
</div>
<div class="large-6 columns">
<div class="input_wrapper string required form_newsletter_subscription_surname"><label class="string required control-label" for="form_newsletter_subscription_surname"><abbr title="campo obbligatorio">*</abbr> cognome</label><input size="20" maxlength="50" class="string required" type="text" name="form_newsletter_subscription[surname]" id="form_newsletter_subscription_surname" /></div>
</div>
<div class="large-6 columns">
<div class="input_wrapper tel optional form_newsletter_subscription_phone"><label class="tel optional control-label" for="form_newsletter_subscription_phone">telefono</label><input size="20" maxlength="50" class="string tel optional" type="tel" name="form_newsletter_subscription[phone]" id="form_newsletter_subscription_phone" /></div>
</div>
<div class="large-6 columns">
<div class="input_wrapper email required form_newsletter_subscription_email"><label class="email required control-label" for="form_newsletter_subscription_email"><abbr title="campo obbligatorio">*</abbr> email</label><input size="20" maxlength="100" class="string email required" type="email" name="form_newsletter_subscription[email]" id="form_newsletter_subscription_email" /></div>
</div>
</div>
<div class="row">
<div class="large-12 columns">
<div class="input_wrapper hidden form_newsletter_subscription_newsletter"><input value="1" class="hidden" type="hidden" name="form_newsletter_subscription[newsletter]" id="form_newsletter_subscription_newsletter" /></div>
<div class="input_wrapper boolean optional form_newsletter_subscription_privacy"><input name="form_newsletter_subscription[privacy]" type="hidden" value="0" /><input class="boolean optional" type="checkbox" value="1" name="form_newsletter_subscription[privacy]" id="form_newsletter_subscription_privacy" /><label class="boolean optional control-label" for="form_newsletter_subscription_privacy">Ho Letto l'Informativa sulla <a data-reveal-id="privacyPopup" href="#">privacy</a> ed Accetto le Condizioni.</label></div>
<div class="input_wrapper hidden form_newsletter_subscription_front_conditions"><input value="true" class="hidden" type="hidden" name="form_newsletter_subscription[front_conditions]" id="form_newsletter_subscription_front_conditions" /></div>
<div class="input_wrapper hidden form_newsletter_subscription_site_id"><input value="1" class="hidden" type="hidden" name="form_newsletter_subscription[site_id]" id="form_newsletter_subscription_site_id" /></div>
<div class="input_wrapper hidden form_newsletter_subscription_site_title"><input value="Discoteche Brescia" class="hidden" type="hidden" name="form_newsletter_subscription[site_title]" id="form_newsletter_subscription_site_title" /></div>
</div>
</div>
<div class="row">
<div class="large-12 columns">
<button name="button" type="submit" class="bt submit">invia messaggio</button>
<button name="button" type="reset" class="bt secondary">annulla</button>
</div>
</div>
</form>
<div aria-hidden aria-labelledby="modalTitle" class="reveal-modal" data-reveal id="privacyPopup" role="dialog">
<h2 id="modalTitle">Privacy</h2>
<div>
<?php include("informativa.php"); ?>
</div>
<a aria-label="Close" class="close-reveal-modal">&#215;</a>
</div>

</div>
</div>
</section>