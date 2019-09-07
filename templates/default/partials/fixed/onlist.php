<section class="sheet">
<div class="contentx">
<div class="form-wrapper top-form-enabled">
<form class="simple_form onlist_form" novalidate="novalidate" id="new_form_onlist" enctype="multipart/form-data" action="{{Link|Get|FORM_ONLIST}}" accept-charset="UTF-8" data-remote="true" method="post">
  <input name="utf8" type="hidden" value="&#x2713;" />
  <div class="row error">
    <div class="large-12 columns">
      <p class="alert-box alert radius error_message" data-alert="">Il seguente modulo non puo essere inviato a causa di alcuni campi mancanti o incorretti, completa correttamente tutti i dati mancanti o incorretti e poi ritenta l'invio</p>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      Compila il form sottostante e verrai ricontattato al più presto.
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">
      <div class="input_wrapper string required form_onlist_name">
        <label class="string required control-label" for="form_onlist_name">
          <abbr title="campo obbligatorio">*</abbr> nome
        </label>
        <input size="20" maxlength="50" class="string" required type="text" name="form_onlist[name]" id="form_onlist_name" />
        <small class="error">inserisci il tuo nome</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper string required form_onlist_surname"><label class="string required control-label" for="form_onlist_surname"><abbr title="campo obbligatorio">*</abbr> cognome</label><input size="20" maxlength="50" class="string required" type="text" name="form_onlist[surname]" id="form_onlist_surname" />
        <small class="error">inserisci il tuo cognome</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper select required form_onlist_location">
        <label class="select required control-label" for="form_onlist_location"><abbr title="campo obbligatorio">*</abbr> discoteca</label>
        <select class="select required" name="form_onlist[location]" id="form_onlist_location">
          <option value="">scegli locale:</option>
          <?php $list = $DB->getLocationsForList();?>
          <?php foreach ($list as $item) { ?>
            <option><?php echo $item["title"]; ?></option>
          <?php } ?>
        </select>
        <small class="error">devi scegliere il locale</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-3 columns">
      <div class="input_wrapper string required form_onlist_day"><label class="string required control-label" for="form_onlist_day"><abbr title="campo obbligatorio">*</abbr> giorno</label><input placeholder="gg/mm/aaaa" size="20" maxlength="10" class="string required date-picker" type="text" name="form_onlist[day]" id="form_onlist_day" />
        <small class="error">inserisci il giorno in cui vuoi prenotare (formato gg/mm/aaaa)</small>
      </div>
    </div>
    <div class="large-3 columns">
      <div class="input_wrapper select required form_onlist_reservation">
        <label class="select required control-label" for="form_onlist_reservation"><abbr title="campo obbligatorio">*</abbr> prenotazione</label>
        <select class="select required" name="form_onlist[reservation]" id="form_onlist_reservation">
          <option value=""></option>
          <option value="lista">lista</option>
          <option value="tavolo">tavolo</option>
        </select>
        <small class="error">devi scegliere il tipo di prenotazione</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper select required form_onlist_recontact">
        <label class="select required control-label" for="form_onlist_recontact"><abbr title="campo obbligatorio">*</abbr> Come vuoi essere ricontattato?</label>
        <select class="select required" name="form_onlist[recontact]" id="form_onlist_recontact">
          <option value=""></option>
          <option value="telefono">telefono</option>
          <option value="e-mail">e-mail</option>
        </select>
        <small class="error">devi specificare come vuoi essere ricontattato</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">
      <div class="input_wrapper tel optional form_onlist_phone"><label class="tel optional control-label" for="form_onlist_phone">telefono</label><input class="string tel optional" type="tel" name="form_onlist[phone]" id="form_onlist_phone" />
        <small class="error">inserisci un numero di telefono valido</small>
      </div>
    </div>
    <div class="large-6 columns">
      <div class="input_wrapper email required form_onlist_email"><label class="email required control-label" for="form_onlist_email"><abbr title="campo obbligatorio">*</abbr> email</label><input size="20" maxlength="200" class="string email required" type="email" name="form_onlist[email]" id="form_onlist_email" />
        <small class="error">inserisci un indirizzo email valido</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper text required form_onlist_info"><label class="text required control-label" for="form_onlist_info"><abbr title="campo obbligatorio">*</abbr> altre informazioni</label>
        <textarea cols="40" rows="4" class="text required" name="form_onlist[info]" id="form_onlist_info"></textarea>
        <small class="error">devi specificare i dettagli della tua richiesta</small>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <div class="input_wrapper boolean optional form_onlist_newsletter"><input name="form_onlist[newsletter]" type="hidden" value="0" /><input class="boolean optional" type="checkbox" value="1" checked="checked" name="form_onlist[newsletter]" id="form_onlist_newsletter" /><label class="boolean optional control-label" for="form_onlist_newsletter">Voglio essere aggiornato sui migliori eventi di <?php echo $currentCity; ?></label></div>
      <div class="input_wrapper boolean optional form_onlist_privacy">
        <input name="form_onlist[privacy]" type="hidden" value="0" />
        <input class="boolean optional required" type="checkbox" value="1" name="form_onlist[privacy]" id="form_onlist_privacy" />
        <label class="boolean optional control-label" for="form_onlist_privacy">Ho Letto l'Informativa sulla <a data-reveal-id="privacyPopup" href="#">privacy</a> ed Accetto le Condizioni.</label>
        <small class="error">devi confermare l' informativa sulla privacy per inviare il messaggio</small>
      </div>
      <div class="input_wrapper hidden form_onlist_front_conditions"><input value="true" class="hidden" type="hidden" name="form_onlist[front_conditions]" id="form_onlist_front_conditions" /></div>
    </div>
  </div>
  <div class="row spaceB">
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