<section class="sheet">
<div class="contentx">
<div class="form-wrapper top-form-enabled">
<form validate="true" class="simple_form contact-form" novalidate="novalidate" id="new_form_location_suggest" enctype="multipart/form-data" action="{{Link|Get|FORM_LOCATION_SUGGEST}}" accept-charset="UTF-8" data-remote="true" method="post">
  <input name="utf8" type="hidden" value="&#x2713;" />
  <input name="form_location_suggest[site_title]" type="hidden" value="<?php echo $ogSiteName; ?>" /> 
    <div class="row error">
    <div class="large-12 columns">
      <p class="alert-box alert radius error_message" data-alert="">Il seguente modulo non puo essere inviato a causa di alcuni campi mancanti o incorretti, completa correttamente tutti i dati mancanti o incorretti e poi ritenta l'invio</p>
    </div>
  </div>
  <div class="large-6 columns form_contact_nickname">
    <div class="input_wrapper string form_contact_name">
      <label class="string control-label" for="form_contact_name">
        nickname
      </label>
      <input size="20" maxlength="50" class="string" type="text" name="form_location_suggest[nickname]" id="form_contact_nickname" />
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">
      <div class="input_wrapper string required form_location_suggest_name"><label class="string required control-label" for="form_location_suggest_name"><abbr title="campo obbligatorio">*</abbr> nome</label><input size="20" maxlength="50" class="string required" type="text" name="form_location_suggest[name]" id="form_location_suggest_name" />
        <small class="error">inserisci il tuo nome</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper string required form_location_suggest_surname"><label class="string required control-label" for="form_location_suggest_surname"><abbr title="campo obbligatorio">*</abbr> cognome</label><input size="20" maxlength="50" class="string required" type="text" name="form_location_suggest[surname]" id="form_location_suggest_surname" />
        <small class="error">inserisci il tuo cognome</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper tel required form_location_suggest_phone"><label class="tel required control-label" for="form_location_suggest_phone"><abbr title="campo obbligatorio">*</abbr> telefono</label><input size="20" maxlength="50" class="string tel required" type="tel" name="form_location_suggest[phone]" id="form_location_suggest_phone" />
        <small class="error">inserisci un numero di telefono valido</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper email required form_location_suggest_email"><label class="email required control-label" for="form_location_suggest_email"><abbr title="campo obbligatorio">*</abbr> email</label><input size="20" maxlength="100" class="string email required" type="email" name="form_location_suggest[email]" id="form_location_suggest_email" />
        <small class="error">inserisci un indirizzo email valido</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper select required form_location_suggest_typo">
        <label class="select required control-label" for="form_location_suggest_typo"><abbr title="campo obbligatorio">*</abbr> categoria</label>
        <select class="select required" name="form_location_suggest[typo]" id="form_location_suggest_typo">
          <option value="">scegli categoria</option>
          <option value="Addio al celibato a Brescia">Addio al celibato a Brescia</option>
          <option value="Addio al nubilato a Brescia">Addio al nubilato a Brescia</option>
          <option value="Aperitivi &amp; happy hour">Aperitivi &amp; happy hour</option>
          <option value="Disco bar &amp; Live music">Disco bar &amp; Live music</option>
          <option value="Disco restaurant">Disco restaurant</option>
          <option value="Discoteche a Brescia">Discoteche a Brescia</option>
          <option value="Discoteche Riccione Rimini">Discoteche Riccione Rimini</option>
          <option value="Divertimento">Divertimento</option>
          <option value="Latino americano">Latino americano</option>
          <option value="Locali Brescia">Locali Brescia</option>
          <option value="Multisale e cinema">Multisale e cinema</option>
          <option value="Night club">Night club</option>
          <option value="Offerta vacanza Rimini e Riccione">Offerta vacanza Rimini e Riccione</option>
          <option value="Offerte feste">Offerte feste</option>
          <option value="Piadinerie e paninoteche">Piadinerie e paninoteche</option>
          <option value="Pub e birrerie">Pub e birrerie</option>
          <option value="Ricette cocktail internazionali">Ricette cocktail internazionali</option>
          <option value="Ristoranti e locali etnici">Ristoranti e locali etnici</option>
          <option value="Ristoranti, pizzerie e osterie ">Ristoranti, pizzerie e osterie </option>
        </select>
        <small class="error">inserisci la tipologia di locale</small>
      </div>
    </div>
    <div class="large-12 columns">
      <div class="input_wrapper string required form_location_suggest_denomination"><label class="string required control-label" for="form_location_suggest_denomination"><abbr title="campo obbligatorio">*</abbr> nome locale</label><input size="20" maxlength="100" class="string required" type="text" name="form_location_suggest[denomination]" id="form_location_suggest_denomination" />
        <small class="error">devi inserire il nome del locale</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">
      <div class="input_wrapper string required form_location_suggest_address_way"><label class="string required control-label" for="form_location_suggest_address_way"><abbr title="campo obbligatorio">*</abbr> via</label><input size="20" maxlength="50" class="string required" type="text" name="form_location_suggest[address_way]" id="form_location_suggest_address_way" />
        <small class="error">devi inserire la via, piazza o altro</small>
      </div>
    </div>
    <div class="large-3 columns">
      <div class="input_wrapper string required form_location_suggest_address_number"><label class="string required control-label" for="form_location_suggest_address_number"><abbr title="campo obbligatorio">*</abbr> n°</label><input size="6" maxlength="6" class="string required" type="text" name="form_location_suggest[address_number]" id="form_location_suggest_address_number" />
        <small class="error">devi inserire il numero civico</small>
      </div>
    </div>
    <div class="large-3 columns">
      <div class="input_wrapper string required form_location_suggest_address_zip"><label class="string required control-label" for="form_location_suggest_address_zip"><abbr title="campo obbligatorio">*</abbr> cap</label><input size="8" maxlength="10" class="string required" type="text" name="form_location_suggest[address_zip]" id="form_location_suggest_address_zip" />
        <small class="error">devi inserire il codice postale</small>
      </div>
    </div>
    <div class="large-8 columns">
      <div class="input_wrapper string required form_location_suggest_address_city"><label class="string required control-label" for="form_location_suggest_address_city"><abbr title="campo obbligatorio">*</abbr> città</label><input size="20" maxlength="50" class="string required" type="text" name="form_location_suggest[address_city]" id="form_location_suggest_address_city" />
        <small class="error">devi inserire la cittá</small>
      </div>
    </div>
    <div class="large-4 columns">
      <div class="input_wrapper string required form_location_suggest_address_province"><label class="string required control-label" for="form_location_suggest_address_province"><abbr title="campo obbligatorio">*</abbr> pr.</label><input size="3" maxlength="2" value="BS" class="string required" type="text" name="form_location_suggest[address_province]" id="form_location_suggest_address_province" />
        <small class="error">devi inserire la provincia</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="row days check_boxes optional form_location_suggest_openings"><label class="check_boxes optional control-label">giorni apertura</label><span class="checkbox"><input class="check_boxes optional" type="checkbox" value="Lunedì" name="form_location_suggest[openings][]" id="form_location_suggest_openings_luned" /><label class="collection_check_boxes" for="form_location_suggest_openings_luned">Lun</label></span><span class="checkbox"><input class="check_boxes optional" type="checkbox" value="Martedì" name="form_location_suggest[openings][]" id="form_location_suggest_openings_marted" /><label class="collection_check_boxes" for="form_location_suggest_openings_marted">Mar</label></span><span class="checkbox"><input class="check_boxes optional" type="checkbox" value="Mercoledì" name="form_location_suggest[openings][]" id="form_location_suggest_openings_mercoled" /><label class="collection_check_boxes" for="form_location_suggest_openings_mercoled">Mer</label></span><span class="checkbox"><input class="check_boxes optional" type="checkbox" value="Giovedì" name="form_location_suggest[openings][]" id="form_location_suggest_openings_gioved" /><label class="collection_check_boxes" for="form_location_suggest_openings_gioved">Gio</label></span><span class="checkbox"><input class="check_boxes optional" type="checkbox" value="Venerdì" name="form_location_suggest[openings][]" id="form_location_suggest_openings_venerd" /><label class="collection_check_boxes" for="form_location_suggest_openings_venerd">Ven</label></span><span class="checkbox"><input class="check_boxes optional" type="checkbox" value="Sabato" name="form_location_suggest[openings][]" id="form_location_suggest_openings_sabato" /><label class="collection_check_boxes" for="form_location_suggest_openings_sabato">Sab</label></span><span class="checkbox"><input class="check_boxes optional" type="checkbox" value="Domenica" name="form_location_suggest[openings][]" id="form_location_suggest_openings_domenica" /><label class="collection_check_boxes" for="form_location_suggest_openings_domenica">Dom</label></span><input type="hidden" name="form_location_suggest[openings][]" value="" /></div>
    </div>
    <div class="large-12 columns">
      <div class="input_wrapper text required form_location_suggest_info"><label class="text required control-label" for="form_location_suggest_info"><abbr title="campo obbligatorio">*</abbr> descrizione locale</label>
        <textarea cols="40" rows="8" class="text required" name="form_location_suggest[info]" id="form_location_suggest_info"></textarea>
        <small class="error">devi specificare la descrizione del locale</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper boolean optional form_location_suggest_newsletter"><input name="form_location_suggest[newsletter]" type="hidden" value="0" /><input class="boolean optional" type="checkbox" value="1" name="form_location_suggest[newsletter]" id="form_location_suggest_newsletter" /><label class="boolean optional control-label" for="form_location_suggest_newsletter">Voglio essere aggiornato sui migliori eventi di Brescia</label></div>
      <div class="input_wrapper boolean optional form_location_suggest_privacy"><input name="form_location_suggest[privacy]" type="hidden" value="0" /><input class="boolean optional required" type="checkbox" value="1" name="form_location_suggest[privacy]" id="form_location_suggest_privacy" /><label class="boolean optional control-label" for="form_location_suggest_privacy">Ho Letto l'Informativa sulla <?php echo $App->iubenda_privacy_link(); ?> ed Accetto le Condizioni.</label>
        <small class="error">devi confermare l' informativa sulla privacy per inviare il messaggio</small>
      </div>
      <div class="input_wrapper hidden form_location_suggest_front_conditions"><input value="true" class="hidden" type="hidden" name="form_location_suggest[front_conditions]" id="form_location_suggest_front_conditions" /></div>
      <div class="input_wrapper hidden form_location_suggest_site_id"><input value="1" class="hidden" type="hidden" name="form_location_suggest[site_id]" id="form_location_suggest_site_id" /></div>
      <div class="input_wrapper hidden form_location_suggest_site_title"><input value="Discoteche Brescia" class="hidden" type="hidden" name="form_location_suggest[site_title]" id="form_location_suggest_site_title" /></div>
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