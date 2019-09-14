<section class="sheet">
<div class="contentx">
<div class="form-wrapper top-form-enabled">
<form validate="true" class="simple_form contact-form" novalidate="novalidate" id="new_form_party" enctype="multipart/form-data" action="{{Link|Get|FORM_PARTY}}" accept-charset="UTF-8" data-remote="true" method="post">
  <input name="utf8" type="hidden" value="&#x2713;" />
  <div class="row error">
    <div class="large-12 columns">
      <p class="alert-box alert radius error_message" data-alert="">Il seguente modulo non puo essere inviato a causa di alcuni campi mancanti o incorretti, completa correttamente tutti i dati mancanti o incorretti e poi ritenta l'invio</p>
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">
      <div class="input_wrapper string required form_party_name"><label class="string required control-label" for="form_party_name"><abbr title="campo obbligatorio">*</abbr> nome</label><input size="20" maxlength="50" class="string required" type="text" name="form_party[name]" id="form_party_name" />
        <small class="error">inserisci il tuo nome</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper string required form_party_surname"><label class="string required control-label" for="form_party_surname"><abbr title="campo obbligatorio">*</abbr> cognome</label><input size="20" maxlength="50" class="string required" type="text" name="form_party[surname]" id="form_party_surname" />
        <small class="error">inserisci il tuo cognome</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper tel required form_party_phone"><label class="tel required control-label" for="form_party_phone"><abbr title="campo obbligatorio">*</abbr> telefono</label><input size="20" maxlength="50" class="string tel required" type="tel" name="form_party[phone]" id="form_party_phone" />
        <small class="error">inserisci un numero di telefono valido</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper email required form_party_email"><label class="email required control-label" for="form_party_email"><abbr title="campo obbligatorio">*</abbr> email</label><input size="20" maxlength="100" class="string email required" type="email" name="form_party[email]" id="form_party_email" />
        <small class="error">inserisci un indirizzo email valido</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper select required form_party_type">
        <label class="select required control-label" for="form_party_type"><abbr title="campo obbligatorio">*</abbr> tipologia festa</label>
        <select class="select required" name="form_party[type]" id="form_party_type">
          <option value="">Scegli una tipologia</option>
          <option value="festa di compleanno">festa di compleanno</option>
          <option value="inaugurazione">inaugurazione</option>
          <option value="festa di addio al nubilato/celibato">festa di addio al nubilato/celibato</option>
          <option value="festa di laurea">festa di laurea</option>
          <option value="cena aziendale">cena aziendale</option>
          <option value="altro">altro</option>
        </select>
        <small class="error">seleziona il tipo di festa che ti interessa</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper text required form_party_info"><label class="text required control-label" for="form_party_info"><abbr title="campo obbligatorio">*</abbr> richieste per la festa</label>
        <textarea cols="40" rows="4" class="text required" name="form_party[info]" id="form_party_info"></textarea>
        <small class="error">devi specificare i dettagli della tua richiesta</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper boolean optional form_party_newsletter"><input name="form_party[newsletter]" type="hidden" value="0" /><input class="boolean optional" type="checkbox" value="1" name="form_party[newsletter]" id="form_party_newsletter" /><label class="boolean optional control-label" for="form_party_newsletter">Voglio essere aggiornato sui migliori eventi di Brescia</label></div>
      <div class="input_wrapper boolean optional form_party_privacy"><input name="form_party[privacy]" type="hidden" value="0" /><input class="boolean optional required" type="checkbox" value="1" name="form_party[privacy]" id="form_party_privacy" /><label class="boolean optional control-label" for="form_party_privacy">Ho Letto l'Informativa sulla <?php echo $App->iubenda_privacy_link(); ?> ed Accetto le Condizioni.</label>
        <small class="error">devi confermare l' informativa sulla privacy per inviare il messaggio</small>
      </div>
      <div class="input_wrapper hidden form_party_front_conditions"><input value="true" class="hidden" type="hidden" name="form_party[front_conditions]" id="form_party_front_conditions" /></div>
      <div class="input_wrapper hidden form_party_site_id"><input value="1" class="hidden" type="hidden" name="form_party[site_id]" id="form_party_site_id" /></div>
      <div class="input_wrapper hidden form_party_site_title"><input value="Discoteche Brescia" class="hidden" type="hidden" name="form_party[site_title]" id="form_party_site_title" /></div>
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