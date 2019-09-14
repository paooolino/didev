<section class="sheet">
<div class="contentx">
<div class="form-wrapper top-form-enabled">
<form validate="true" class="simple_form contact-form" novalidate="novalidate" id="new_form_contact" enctype="multipart/form-data" action="{{Link|Get|FORM_CONTACT}}" accept-charset="UTF-8" data-remote="true" method="post">
  <input name="utf8" type="hidden" value="&#x2713;" />
  <div class="row error">
    <div class="large-12 columns">
      <p class="alert-box alert radius error_message" data-alert="">Il seguente modulo non puo essere inviato a causa di alcuni campi mancanti o incorretti, completa correttamente tutti i dati mancanti o incorretti e poi ritenta l'invio</p>
    </div>
  </div>  
  <div class="row">
    <div class="large-6 columns">
      <div class="input_wrapper string required form_contact_name"><label class="string required control-label" for="form_contact_name"><abbr title="campo obbligatorio">*</abbr> nome</label><input size="20" maxlength="50" class="string required" type="text" name="form_contact[name]" id="form_contact_name" />
        <small class="error">inserisci il tuo nome</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper string required form_contact_surname"><label class="string required control-label" for="form_contact_surname"><abbr title="campo obbligatorio">*</abbr> cognome</label><input size="20" maxlength="50" class="string required" type="text" name="form_contact[surname]" id="form_contact_surname" />
        <small class="error">inserisci il tuo cognome</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper tel required form_contact_phone"><label class="tel required control-label" for="form_contact_phone"><abbr title="campo obbligatorio">*</abbr> telefono</label><input size="20" maxlength="50" class="string tel required" type="tel" name="form_contact[phone]" id="form_contact_phone" />
        <small class="error">inserisci un numero di telefono valido</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper email required form_contact_email"><label class="email required control-label" for="form_contact_email"><abbr title="campo obbligatorio">*</abbr> email</label><input size="20" maxlength="100" class="string email required" type="email" name="form_contact[email]" id="form_contact_email" />
        <small class="error">inserisci un indirizzo email valido</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper text required form_contact_info"><label class="text required control-label" for="form_contact_info"><abbr title="campo obbligatorio">*</abbr> altre informazioni</label>
        <textarea cols="40" rows="4" class="text required" name="form_contact[info]" id="form_contact_info"></textarea>
        <small class="error">devi specificare il motivo del tuo contatto</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper boolean optional form_contact_newsletter"><input name="form_contact[newsletter]" type="hidden" value="0" /><input class="boolean optional" type="checkbox" value="1" name="form_contact[newsletter]" id="form_contact_newsletter" /><label class="boolean optional control-label" for="form_contact_newsletter">Voglio essere aggiornato sui migliori eventi di Brescia</label></div>
      <div class="input_wrapper boolean optional form_contact_privacy"><input name="form_contact[privacy]" type="hidden" value="0" /><input class="boolean optional required" type="checkbox" value="1" name="form_contact[privacy]" id="form_contact_privacy" /><label class="boolean optional control-label" for="form_contact_privacy">Ho Letto l'Informativa sulla <?php echo $App->iubenda_privacy_link(); ?> ed Accetto le Condizioni.</label>
        <small class="error">devi confermare l' informativa sulla privacy per inviare il messaggio</small>
      </div>
      <div class="input_wrapper hidden form_contact_front_conditions"><input value="true" class="hidden" type="hidden" name="form_contact[front_conditions]" id="form_contact_front_conditions" /></div>
      <div class="input_wrapper hidden form_contact_site_id"><input value="1" class="hidden" type="hidden" name="form_contact[site_id]" id="form_contact_site_id" /></div>
      <div class="input_wrapper hidden form_contact_site_title"><input value="Discoteche Brescia" class="hidden" type="hidden" name="form_contact[site_title]" id="form_contact_site_title" /></div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <button name="button" type="submit" class="bt submit">invia messaggio</button>
      <button name="button" type="reset" class="bt secondary">annulla</button>
    </div>
  </div>
</form>

</div>
</div>
</section>