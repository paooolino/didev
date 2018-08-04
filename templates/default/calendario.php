              <div class="inner">
                <div class="show-for-large-up bticons icalendar">
                  <p>
                    Calendario eventi
                    <i class="fa fa-calendar"></i>
                    <span class="bigrounded"><?php echo $calendar["tot_events"]; ?></span>
                  </p>
                </div>
                <div class="show-for-large-up">
                  <div id="events_calendar">
                    <header class="calendar-header">
                      <?php echo $calendar["prev_link"]; ?>
                      <span><?php echo $calendar["current_month"]; ?> <?php echo $calendar["current_year"]; ?></span>
                      <?php echo $calendar["next_link"]; ?>
                    </header>
                    <table>
                      <thead>
                        <tr><th>L</th><th>M</th><th>M</th><th>G</th><th>V</th><th>S</th><th>D</th></tr>
                      </thead>
                      <tbody>
                        <?php foreach ($calendar["dayRows"] as $cr) { ?>
                          <tr>
                            <?php foreach ($cr as $cd) { ?>
                              <td class="day <?php echo $cd["dayTimeReference"]; ?> <?php echo $cd["whichmonth"]; ?>-month wday-<?php echo $cd["weekdaynumber"]; ?> has-events">
                                <?php if (!$cd["hasevents"]) { ?>
                                  <?php echo $cd["daynumber"]; ?>
                                <?php } else { ?>
                                  <a title="Eventi a Brescia <?php echo $cd["daynumber"]; ?> <?php echo $cd["monthName"]; ?>" href="<?php echo $cd["url"]; ?>">
                                    <?php echo $cd["daynumber"]; ?>
                                  </a>
                                <?php } ?>
                              </td>
                            <?php } ?>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="todayEvents bticons icalendar">
                  <a title="eventi stasera a Brescia" href="{{Link|Get|EVENTI_STASERA}}">Eventi stasera
                    <i class="fa fa-calendar"></i>
                    <span class="bigrounded"><?php echo $calendar["tot_events_today"]; ?></span>
                  </a>
                </div>
                <div class="weekendEvents bticons icalendar">
                  <a title="eventi week end a Brescia" href="{{Link|Get|EVENTI_WEEKEND}}">Eventi weekend
                    <i class="fa fa-calendar"></i>
                    <span class="bigrounded"><?php echo $calendar["tot_events_weekend"]; ?></span>
                  </a>
                </div>
              </div>