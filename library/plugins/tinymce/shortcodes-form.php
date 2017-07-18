<?php
/**
 * Shortcode Generator Formular
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	tinymce
 */

add_action('wp_ajax_at_shortcodes_form', 'at_shortcodes_form');
function at_shortcodes_form() {
    ?>
    <div id="endcore-shortcodes-form">
        <div class="endcore-shortcodes-form">
            <div class="endcore-shortcodes-form-top">
                <div class="endcore-shortcodes-form-types">
                    <ul>
                        <li class="endcore-shortcodes-form-type-grid active" type="grid"><a href="#">Grid</a></li>
                        <li class="endcore-shortcodes-form-type-button" type="button"><a href="#">Buttons</a></li>
                        <li class="endcore-shortcodes-form-type-alert" type="alert"><a href="#">Alerts</a></li>
                        <li class="endcore-shortcodes-form-type-media" type="media"><a href="#">Media</a></li>
                        <li class="endcore-shortcodes-form-type-tabs" type="tabs"><a href="#">Tabs</a></li>
                        <li class="endcore-shortcodes-form-type-accordion" type="accordion"><a href="#">Accordion</a></li>
                        <li class="endcore-shortcodes-form-type-blogposts" type="blogposts"><a href="#">Blog-Beiträge</a></li>
                        <li class="endcore-shortcodes-form-type-responsive" type="responsive"><a href="#">Responsive Content</a></li>
                        <li class="endcore-shortcodes-form-type-profiles" type="profiles"><a href="#">Profile</a></li>
                        <li class="endcore-shortcodes-form-type-signup_form" type="signup_form"><a href="#">Anmeldeformular</a></li>
                    </ul>
                </div>
                <!-- end types -->
                <div class="endcore-shortcodes-form-tabs">
                    <!-- COLUMNS -->
                    <div class="endcore-shortcodes-form-tab active" id="endcore-shortcodes-form-tab_grid"
                         style="display:block">
                        <h2>Grid</h2>
                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td colspan="2">
                                        <div class="column-structures">
                                            <label>Wähle das gewünschte Layout aus</label>
                                            <a href="#" class="active" split="50|50"><span
                                                    style="width:50%"><i>&frac12;</i></span><span
                                                    style="width:50%"><i>&frac12;</i></span></a>

                                            <div class="clearfix"></div>
                                            <a href="#" split="33|33|33"><span
                                                    style="width:33%"><i>&frac13;</i></span><span
                                                    style="width:33%"><i>&frac13;</i></span><span
                                                    style="width:33%"><i>&frac13;</i></span></a>
                                            <a href="#" split="67|33"><span
                                                    style="width:67%"><i>&frac23;</i></span><span
                                                    style="width:33%"><i>&frac13;</i></span></a>
                                            <a href="#" split="33|67"><span
                                                    style="width:33%"><i>&frac13;</i></span><span
                                                    style="width:67%"><i>&frac23;</i></span></a>

                                            <div class="clearfix"></div>
                                            <a href="#" split="25|25|25|25"><span
                                                    style="width:25%"><i>&frac14;</i></span><span
                                                    style="width:25%"><i>&frac14;</i></span><span
                                                    style="width:25%"><i>&frac14;</i></span><span
                                                    style="width:25%"><i>&frac14;</i></span></a>
                                            <a href="#" split="50|25|25"><span
                                                    style="width:50%"><i>&frac12;</i></span><span
                                                    style="width:25%"><i>&frac14;</i></span><span
                                                    style="width:25%"><i>&frac14;</i></span></a>
                                            <a href="#" split="25|25|50"><span
                                                    style="width:25%"><i>&frac14;</i></span><span
                                                    style="width:25%"><i>&frac14;</i></span><span
                                                    style="width:50%"><i>&frac12;</i></span></a>
                                            <a href="#" split="25|50|25"><span
                                                    style="width:25%"><i>&frac14;</i></span><span
                                                    style="width:50%"><i>&frac12;</i></span><span
                                                    style="width:25%"><i>&frac14;</i></span></a>
                                            <input style="display:none" type="text" fieldname="structure"
                                                   value="50|50"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Spalte 1</label></th>
                                    <td><textarea fieldname="col"></textarea></td>
                                </tr>
                                <tr>
                                    <th><label>Spalte 2</label></th>
                                    <td><textarea fieldname="col"></textarea></td>
                                </tr>
                                <tr>
                                    <th><label>Spalte 3</label></th>
                                    <td><textarea fieldname="col" disabled="disabled"></textarea></td>
                                </tr>
                                <tr>
                                    <th><label>Spalte 4</label></th>
                                    <td><textarea fieldname="col" disabled="disabled"></textarea></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- end tab -->
                    <!-- BUTTON -->
                    <div class="endcore-shortcodes-form-tab" id="endcore-shortcodes-form-tab_button">
                        <h2>Buttons</h2>
                        <p>Einige Beispiele findest du hier: http://demo.datingtheme.io/content/typografie/</p>
                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <th><label>Text</label></th>
                                    <td>
                                        <input type="text" fieldname="button_text" />
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Link</label></th>
                                    <td>
                                        <input type="text" fieldname="button_href" />
                                        <span class="tip">Externe URLs müssen http:// oder https:// enthalten!</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Farbe</label></th>
                                    <td>
                                        <select fieldname="button_color">
                                            <option value="btn-primary" selected="selected">Custom Farbe (Customizer, btn-primary)</option>
                                            <option value="btn-grayl">Hellgrau (btn-grayl)</option>
                                            <option value="btn-gray">Grau (btn-gray)</option>
                                            <option value="btn-grayd">Dunkelgrau (btn-grayd)</option>
                                            <option value="btn-black">Schwarz (btn-black)</option>
                                            <option value="btn-default">Weiß (btn-default)</option>
                                            <option value="btn-success">Grün (btn-success)</option>
                                            <option value="btn-info">Blau (btn-info)</option>
                                            <option value="btn-warning">Orange (btn-warning)</option>
                                            <option value="btn-danger">Rot (btn-danger)</option>
                                            <option value="btn-link">Transparent (btn-link)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Größe</label></th>
                                    <td>
                                        <select fieldname="button_size">
                                            <option value="btn-xs">Extra Klein</option>
                                            <option value="btn-sm">Klein</option>
                                            <option value="btn-md" selected="selected">Normal</option>
                                            <option value="btn-lg">Groß</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Form</label></th>
                                    <td>
                                        <select fieldname="button_shape">
                                            <option value="btn-square">Eckig (btn-square)</option>
                                            <option value="btn-rounded" selected="selected">Leicht Rund (btn-rounded)</option>
                                            <option value="btn-round">Rund (btn-round)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Kontur (Outline)</label></th>
                                    <td>
                                        <select fieldname="button_outline">
                                            <option value="false" selected="selected">Nein</option>
                                            <option value="true">Ja</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Volle Breite (Block)</label></th>
                                    <td>
                                        <select fieldname="button_block">
                                            <option value="false" selected="selected">Nein</option>
                                            <option value="true">Ja</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Icon</label></th>
                                    <td>
                                        <input type="text" fieldname="button_icon" placeholder="fa-download"/>
                                        <span class="tip">Du findest alle Möglichen Icons hier: http://fortawesome.github.io/Font-Awesome/icons/</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Position des Icons</label></th>
                                    <td>
                                        <select fieldname="button_icon_pos">
                                            <option value="" selected="selected"></option>
                                            <option value="left">Links</option>
                                            <option value="right">Rechts</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Link im neuen Tab öffnen?</label></th>
                                    <td>
                                        <select fieldname="button_target">
                                            <option value="" selected="selected">Nein</option>
                                            <option value="_blank">Ja</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Nofollow?</label></th>
                                    <td>
                                        <select fieldname="button_rel">
                                            <option value="" selected="selected">Nein</option>
                                            <option value="nofollow">Ja</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Weitere CSS-Klasse(n)</label></th>
                                    <td>
                                        <input type="text" fieldname="button_class" />
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div><!-- end tab -->
                    <!-- ALERT -->
                    <div class="endcore-shortcodes-form-tab" id="endcore-shortcodes-form-tab_alert">
                        <h2>Alerts</h2>
                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <th><label>Style</label></th>
                                    <td>
                                        <select fieldname="alert_style">
                                            <option value="success" selected="selected">Grün</option>
                                            <option value="info">Blau</option>
                                            <option value="warning">Gelb</option>
                                            <option value="danger">Rot</option>
                                        </select>
                                        <span class="tip">Ein Beispiel findest du hier: http://getbootstrap.com/components/#alerts</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Inhalt</label></th>
                                    <td>
                                        <textarea fieldname="alert_content"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- end tab -->
                    <!-- MEDIA -->
                    <div class="endcore-shortcodes-form-tab" id="endcore-shortcodes-form-tab_media">
                        <h2>Media</h2>

                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <th><label>Style</label></th>
                                    <td>
                                        <select fieldname="media_style">
                                            <option value="left" selected="selected">Objekt Links</option>
                                            <option value="right">Objekt rechts</option>
                                        </select>
                                        <span class="tip">Ein Beispiel findest du hier: http://getbootstrap.com/components/#media</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Horizontale Ausrichtung</label></th>
                                    <td>
                                        <select fieldname="media_aligned">
                                            <option value="top" selected="selected">Oben</option>
                                            <option value="middle">Mittig</option>
                                            <option value="bottom">Unten</option>
                                        </select>
                                        <span class="tip">Horizontale Ausrichtung des Bildes</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Objekt</label></th>
                                    <td>
                                        <input type="text" fieldname="media_object" placeholder=""/>
                                        <span
                                            class="tip">Bitte gebe hier z.B. ein Bild an (komplettes HTML-Tag!).</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Inhalt</label></th>
                                    <td>
                                        <textarea fieldname="media_content"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- end tab -->
                    <!-- TABS -->
                    <div class="endcore-shortcodes-form-tab" id="endcore-shortcodes-form-tab_tabs">
                        <h2>Tabs hinzufügen</h2>

                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <th><label>Tab 1</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Tab 2</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Tab 3</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Tab 4</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Tab 5</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Tab 6</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Tab 7</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Tab 8</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Tab 9</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Tab 10</label></th>
                                    <td><label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- end tab -->
                    <!-- ACCORDIONS -->
                    <div class="endcore-shortcodes-form-tab" id="endcore-shortcodes-form-tab_accordion">
                        <h2>Accordions hinzufügen</h2>

                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0">
                                <tr class="toggle-items">
                                    <th><label>Toggle 1</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="toggle-items">
                                    <th><label>Toggle 2</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="toggle-items">
                                    <th><label>Toggle 3</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="toggle-items">
                                    <th><label>Toggle 4</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="toggle-items">
                                    <th><label>Toggle 5</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="toggle-items">
                                    <th><label>Toggle 6</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="toggle-items">
                                    <th><label>Toggle 7</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="toggle-items">
                                    <th><label>Toggle 8</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="toggle-items">
                                    <th><label>Toggle 9</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="toggle-items">
                                    <th><label>Toggle 10</label></th>
                                    <td>
                                        <label>Label</label><input type="text" value="" fieldname="label"/>
                                        <label>Text</label><textarea fieldname="text"></textarea>
                                        <label>Ausgangszustand</label><select fieldname="onload">
                                            <option value="">Geschlossen</option>
                                            <option value="true">Geöffnet</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- end tab -->
                    <!-- BLOGPOSTS -->
                    <div class="endcore-shortcodes-form-tab" id="endcore-shortcodes-form-tab_blogposts">
                        <h2>Blog-Beiträge</h2>
                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0" id="posts-table">
                                <tr>
                                    <th><label>Anzahl von Beiträgen</label></th>
                                    <td>
                                        <input type="number" value="" fieldname="limit" placeholder="4"/>
                                        <span class="tip">
                                            Wieviele Beiträge sollen ausgegeben werden?
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Sortieren nach</label></th>
                                    <td>
                                        <select fieldname="orderby">
                                            <option value="date" selected="selected">Datum</option>
                                            <option value="ID">ID</option>
                                            <option value="title">Titel</option>
                                            <option value="modified">Zuletzt geändert</option>
                                            <option value="rand">Zufällig</option>
                                            <option value="comment_count">Anzahl Kommentare</option>
                                            <option value="menu_order">Menü Reihenfolge</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Reihenfolge</label></th>
                                    <td>
                                        <select fieldname="order">
                                            <option value="desc" selected="selected">Absteigend</option>
                                            <option value="asc">Aufsteigend</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Stil</label></th>
                                    <td>
                                        <select fieldname="layout">
                                            <option value="small">Thumbnail</option>
                                            <option value="large">Large</option>
                                            <option value="list">Einfache Liste</option>
                                            <option value="grid">Grid</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div><!-- end tab -->
                    <!-- RESPONSIVE CONTENT -->
                    <div class="endcore-shortcodes-form-tab" id="endcore-shortcodes-form-tab_responsive">
                        <h2>Responsive Content</h2>
                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <th><label>Aktion</label></th>
                                    <td>
                                        <select fieldname="aktion">
                                            <option value="visible" selected="selected">Anzeigen</option>
                                            <option value="hidden">Verstecken</option>
                                        </select>
                                        <span class="tip">Was soll mit dem Inhalt geschehen? Er kann versteckt oder angezeigt werden</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Devices</label></th>
                                    <td>
                                        <select fieldname="screen" multiple>
                                            <option value="xs">Smartphone</option>
                                            <option value="sm">Tablets</option>
                                            <option value="md">Notebooks / kleinere Desktops</option>
                                            <option value="lg">Desktops</option>
                                        </select>
                                        <span class="tip">Wähle hier aus, auf welchen Devices der Inhalt versteckt/angezeigt werden soll.</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Stil</label></th>
                                    <td>
                                        <select fieldname="display">
                                            <option value="block">Block</option>
                                            <option value="inline">Inline</option>
                                            <option value="inline-block">Inline-Block</option>
                                        </select>
                                        <span class="tip">Gilt nur für "Anzeigen". Wenn du innerhalb eines Textes besimmte Wörter ausblenden/anzeigen willst, wähle hier "Inline". Wenn du Block wählst, bricht der Inhalt in einen Absatz um.</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Inhalt</label></th>
                                    <td>
                                        <textarea fieldname="responsive_content"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div><!-- end tab -->
                    <!-- PROFILE -->
                    <div class="endcore-shortcodes-form-tab" id="endcore-shortcodes-form-tab_profiles">
                        <h2>Profile</h2>
                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <th><label>Anzahl der Profile</label></th>
                                    <td>
                                        <input type="number" value="" fieldname="limit" placeholder="4"/>
                                        <span class="tip">
                                            Wieviele Profile sollen ausgegeben werden?
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Geschlecht</label></th>
                                    <td>
                                        <select fieldname="gender">
                                            <option value="">-</option>
                                            <option value="male">Männlich</option>
                                            <option value="female">Weiblich</option>
                                            <option value="T">Transvestiten</option>
                                            <option value="S">Shemale/Transexuelle</option>
                                            <option value="FF">Paare (Frau/Frau)</option>
                                            <option value="FM">Paare (Frau/Mann)</option>
                                            <option value="MM">Paare (Mann/Mann)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Alter (von)</label></th>
                                    <td>
                                        <input type="number" value="" fieldname="age_from" placeholder=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Alter (bis)</label></th>
                                    <td>
                                        <input type="number" value="" fieldname="age_to" placeholder=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Figur</label></th>
                                    <td>
                                        <select fieldname="figure">
                                            <option value="">-</option>
                                            <option value="Schlank">Schlank</option>
                                            <option value="Sportlich">Sportlich</option>
                                            <option value="Normal">Normal</option>
                                            <option value="Mollig">Mollig</option>
                                            <option value="Dick">Dick</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Haarfarbe</label></th>
                                    <td>
                                        <select fieldname="hair_color">
                                            <option value="">-</option>
                                            <option value="Blond">Blond</option>
                                            <option value="Dunkelblond">Dunkelblond</option>
                                            <option value="Braun">Braun</option>
                                            <option value="Schwarz">Schwarz</option>
                                            <option value="Rot">Rot</option>
                                            <option value="Andere">Andere</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Größe (von)</label></th>
                                    <td>
                                        <input type="number" value="" fieldname="size_from" placeholder=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Größe (bis)</label></th>
                                    <td>
                                        <input type="number" value="" fieldname="size_to" placeholder=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Gewicht (von)</label></th>
                                    <td>
                                        <input type="number" value="" fieldname="weight_from" placeholder=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Gewicht (bis)</label></th>
                                    <td>
                                        <input type="number" value="" fieldname="weight_to" placeholder=""/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Sortieren nach</label></th>
                                    <td>
                                        <select fieldname="orderby">
                                            <option value="date">Datum</option>
                                            <option value="ID">ID</option>
                                            <option value="title">Titel</option>
                                            <option value="modified">Zuletzt geändert</option>
                                            <option value="rand" selected="selected">Zufällig</option>
                                            <option value="gender">Geschlecht</option>
                                            <option value="age">Alter</option>
                                            <option value="weight">Gewicht</option>
                                            <option value="size">Größe</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Reihenfolge</label></th>
                                    <td>
                                        <select fieldname="order">
                                            <option value="desc" selected="selected">Absteigend</option>
                                            <option value="asc">Aufsteigend</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Stil</label></th>
                                    <td>
                                        <select fieldname="layout">
                                            <option value="grid">Grid</option>
                                            <option value="list">Liste</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Einträge pro Reihe</label></th>
                                    <td>
                                        <select fieldname="per_row">
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6" selected>6</option>
                                        </select>
                                        <span class="tip">
                                            Gilt nur für die Grid-Ansicht
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div><!-- end tab -->
                    <!-- SIGNUP FORM -->
                    <div class="endcore-shortcodes-form-tab" id="endcore-shortcodes-form-tab_signup_form">
                        <h2>Anmeldeformular</h2>
                        <div class="endcore-shortcodes-form-fields">
                            <table cellpadding="0" cellspacing="0">
                                <p>Für diesen Shortcode sind keine Einstellungen notwendig. Dieser Shortcode wird einfach nur platziert.</p>
                            </table>
                        </div>
                    </div><!-- end tab -->
                </div>
            </div>
            <div class="endcore-shortcodes-submit">
                <input style="display:none" id="endcore-shortcodes-form-type" value="button"/>
                <textarea style="display:none" id="endcore-shortcodes-form-code-to-add"></textarea>
                <input type="button" id="endcore_shortcodes-submit" class="button-primary" value="Shortcode einfügen" name="submit"/>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        /**
        * Global vars
        *
        */
        var form = jQuery('#endcore-shortcodes-form');
        var endcore_shortcode_type = "grid";
        var endcore_shortcode_code = "";

        /**
        * Function
        * change shortcode tabs
        */
        jQuery('.endcore-shortcodes-form-types ul li a').click(function(){
            endcore_shortcode_type = jQuery(this).parent('li').attr('type');
            jQuery('input#endcore-shortcodes-form-type').val(endcore_shortcode_type);
            jQuery('.endcore-shortcodes-form-tab').hide();
            jQuery('#endcore-shortcodes-form-tab_'+endcore_shortcode_type).show();
            jQuery('.endcore-shortcodes-form-types .active').removeClass('active');
            jQuery(this).parent('li').addClass('active');
            jQuery('.endcore-shortcodes-form .endcore-shortcodes-form-types').css({"height":jQuery('.endcore-shortcodes-form-tabs').outerHeight()});
            return false;
        });

        /**
         * Function
         * Grids
         */
        var num_of_columns = 2;
        jQuery('.column-structures a').click(function() {
            jQuery('.column-structures a').removeClass('active');
            jQuery(this).addClass('active');
            jQuery('.column-structures input').val(jQuery(this).attr('split'));
            num_of_columns = jQuery(this).attr('split');
            num_of_columns = num_of_columns.split('|');
            num_of_columns = num_of_columns.length;

            jQuery('#endcore-shortcodes-form-tab_grid textarea').attr({'disabled':'disabled'});
            var i = -1;
            while(i < (num_of_columns - 1)) {
                i++;
                jQuery('#endcore-shortcodes-form-tab_grid textarea').eq(i).removeAttr('disabled');
            }

            return false;
        });

        /**
        * Function
        * shortcode form submit
        */
        form.find('#endcore_shortcodes-submit').click(function(){
            endcore_shortcode_code = '';
            console.log(endcore_shortcode_type);
            if(endcore_shortcode_type == "products") {
                /**
                 * Produkte Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[produkte ';


                jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' input, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' select, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {

                    if(jQuery(this).attr('fieldname') != "" && jQuery(this).attr('fieldname') != undefined && jQuery(this).attr('fieldname') != "products_select") {
                        if(jQuery(this).val() !== undefined && jQuery(this).val() !== "" && jQuery(this).val() !== null) {
                            endcore_shortcode_code = endcore_shortcode_code + ' ' + jQuery(this).attr('fieldname') + '="' + jQuery(this).val() + '"';
                        }
                    }

                });
                endcore_shortcode_code = endcore_shortcode_code + ']';
            } if(endcore_shortcode_type == "infobox") {
                /**
                 * Infobox Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[infobox ';


                jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' input, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' select, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {

                    if(jQuery(this).attr('fieldname') != "" && jQuery(this).attr('fieldname') != undefined) {
                        if(jQuery(this).val() !== undefined && jQuery(this).val() !== "" && jQuery(this).val() !== null) {
                            endcore_shortcode_code = endcore_shortcode_code + ' ' + jQuery(this).attr('fieldname') + '="' + jQuery(this).val() + '"';
                        }
                    }

                });
                endcore_shortcode_code = endcore_shortcode_code + ']';
            } else if(endcore_shortcode_type == "price_compare") {
                /**
                 * Preisvergleich Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[preisvergleich ';


                jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' input, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' select, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {

                    if(jQuery(this).attr('fieldname') != "" && jQuery(this).attr('fieldname') != undefined) {
                        if(jQuery(this).val() !== undefined && jQuery(this).val() !== "" && jQuery(this).val() !== null) {
                            endcore_shortcode_code = endcore_shortcode_code + ' ' + jQuery(this).attr('fieldname') + '="' + jQuery(this).val() + '"';
                        }
                    }

                });
                endcore_shortcode_code = endcore_shortcode_code + ']';
            } else if(endcore_shortcode_type == "grid") {
                /**
                 * Grid Shortcode
                 */
                var columns = jQuery('.column-structures').find('.active').attr('split');
                var col_counter = 0;
                var col_var = 0;

                if(columns == "50|50") {
                    col_var = "6";

                    endcore_shortcode_code = endcore_shortcode_code + '[row]';
                    jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {
                        if(jQuery(this).attr('disabled') == "disabled") { } else {
                            col_counter++;
                            endcore_shortcode_code = endcore_shortcode_code + '[col class="col-sm-'+col_var+'"]' + jQuery(this).val() + '[/col]';
                        }
                    });
                    endcore_shortcode_code = endcore_shortcode_code + '[/row]';
                } else if(columns == "33|33|33") {
                    col_var = "4";

                    endcore_shortcode_code = endcore_shortcode_code + '[row]';
                    jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {
                        if(jQuery(this).attr('disabled') == "disabled") { } else {
                            col_counter++;
                            endcore_shortcode_code = endcore_shortcode_code + '[col class="col-sm-'+col_var+'"]' + jQuery(this).val() + '[/col]';
                        }
                    });
                    endcore_shortcode_code = endcore_shortcode_code + '[/row]';
                } else if(columns == "67|33") {
                    col_var = "8";

                    endcore_shortcode_code = endcore_shortcode_code + '[row]';
                    jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {
                        if(jQuery(this).attr('disabled') == "disabled") { } else {
                            col_counter++;
                            if(col_counter == "2") col_var = "4";
                            endcore_shortcode_code = endcore_shortcode_code + '[col class="col-sm-'+col_var+'"]' + jQuery(this).val() + '[/col]';
                        }
                    });
                    endcore_shortcode_code = endcore_shortcode_code + '[/row]';
                } else if(columns == "33|67") {
                    col_var = "4";

                    endcore_shortcode_code = endcore_shortcode_code + '[row]';
                    jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {
                        if(jQuery(this).attr('disabled') == "disabled") { } else {
                            col_counter++;
                            if(col_counter == "2") col_var = "8";
                            endcore_shortcode_code = endcore_shortcode_code + '[col class="col-sm-'+col_var+'"]' + jQuery(this).val() + '[/col]';
                        }
                    });
                    endcore_shortcode_code = endcore_shortcode_code + '[/row]';
                } else if(columns == "25|25|25|25") {
                    col_var = "3";

                    endcore_shortcode_code = endcore_shortcode_code + '[row]';
                    jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {
                        if(jQuery(this).attr('disabled') == "disabled") { } else {
                            col_counter++;
                            endcore_shortcode_code = endcore_shortcode_code + '[col class="col-sm-'+col_var+'"]' + jQuery(this).val() + '[/col]';
                        }
                    });
                    endcore_shortcode_code = endcore_shortcode_code + '[/row]';
                } else if(columns == "50|25|25") {
                    col_var = "6";

                    endcore_shortcode_code = endcore_shortcode_code + '[row]';
                    jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {
                        if(jQuery(this).attr('disabled') == "disabled") { } else {
                            col_counter++;
                            if(col_counter == "2" || col_counter == "3") col_var = "3";
                            endcore_shortcode_code = endcore_shortcode_code + '[col class="col-sm-'+col_var+'"]' + jQuery(this).val() + '[/col]';
                        }
                    });
                    endcore_shortcode_code = endcore_shortcode_code + '[/row]';
                } else if(columns == "25|25|50") {
                    col_var = "3";

                    endcore_shortcode_code = endcore_shortcode_code + '[row]';
                    jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {
                        if(jQuery(this).attr('disabled') == "disabled") { } else {
                            col_counter++;
                            if(col_counter == "3") col_var = "6";
                            endcore_shortcode_code = endcore_shortcode_code + '[col class="col-sm-'+col_var+'"]' + jQuery(this).val() + '[/col]';
                        }
                    });
                    endcore_shortcode_code = endcore_shortcode_code + '[/row]';
                } else if(columns == "25|50|25") {
                    col_var = "3";

                    endcore_shortcode_code = endcore_shortcode_code + '[row]';
                    jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {
                        if(jQuery(this).attr('disabled') == "disabled") { } else {
                            col_counter++;
                            if(col_counter == "2") col_var = "6";
                            if(col_counter == "3") col_var = "3";
                            endcore_shortcode_code = endcore_shortcode_code + '[col class="col-sm-'+col_var+'"]' + jQuery(this).val() + '[/col]';
                        }
                    });
                    endcore_shortcode_code = endcore_shortcode_code + '[/row]';
                }
            } else if(endcore_shortcode_type == "button") {
                /**
                 * Button Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[button';
                endcore_shortcode_code = endcore_shortcode_code + ' color="' + jQuery('select[fieldname=button_color]').val() + '"';
                endcore_shortcode_code = endcore_shortcode_code + ' size="' + jQuery('select[fieldname=button_size]').val() + '"';
                endcore_shortcode_code = endcore_shortcode_code + ' shape="' + jQuery('select[fieldname=button_shape]').val() + '"';
                endcore_shortcode_code = endcore_shortcode_code + ' outline="' + jQuery('select[fieldname=button_outline]').val() + '"';
                endcore_shortcode_code = endcore_shortcode_code + ' block="' + jQuery('select[fieldname=button_block]').val() + '"';
                if(jQuery('input[fieldname=button_icon]').val()) {
                    endcore_shortcode_code = endcore_shortcode_code + ' icon="' + jQuery('input[fieldname=button_icon]').val() + '"';
                    endcore_shortcode_code = endcore_shortcode_code + ' icon_position="' + jQuery('select[fieldname=button_icon_pos]').val() + '"';
                }
                endcore_shortcode_code = endcore_shortcode_code + ' target="' + jQuery('select[fieldname=button_target]').val() + '"';
                endcore_shortcode_code = endcore_shortcode_code + ' rel="' + jQuery('select[fieldname=button_rel]').val() + '"';
                if(jQuery('input[fieldname=button_href]').val()) {
                    endcore_shortcode_code = endcore_shortcode_code + ' href="' + jQuery('input[fieldname=button_href]').val() + '"';
                }
                if(jQuery('input[fieldname=button_class]').val()) {
                    endcore_shortcode_code = endcore_shortcode_code + ' class="' + jQuery('input[fieldname=button_class]').val() + '"';
                }
                endcore_shortcode_code = endcore_shortcode_code + ']';
                if(jQuery('input[fieldname=button_text]').val()) {
                    endcore_shortcode_code = endcore_shortcode_code + jQuery('input[fieldname=button_text]').val();
                }
                endcore_shortcode_code = endcore_shortcode_code + '[/button]'

            } else if(endcore_shortcode_type == "alert") {
                /**
                 * Alert Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[alert style="'+jQuery('select[fieldname=alert_style]').val()+'"';
                endcore_shortcode_code = endcore_shortcode_code +  ']' + jQuery('textarea[fieldname=alert_content]').val() + '[/alert]';

            } else if(endcore_shortcode_type == "media") {
                /**
                 * Media Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[media]';

                if(jQuery('select[fieldname=media_style]').val() == 'left') {
                    endcore_shortcode_code = endcore_shortcode_code + '[media_object style="left"';
                    endcore_shortcode_code = endcore_shortcode_code + ' aligned="'+jQuery('select[fieldname=media_aligned]').val()+'"]';
                    endcore_shortcode_code = endcore_shortcode_code + jQuery('input[fieldname=media_object]').val();
                    endcore_shortcode_code = endcore_shortcode_code + '[/media_object]';
                }

                endcore_shortcode_code = endcore_shortcode_code + '[media_body] ' + jQuery('textarea[fieldname=media_content]').val() + '[/media_body]';

                if(jQuery('select[fieldname=media_style]').val() == 'right') {
                    endcore_shortcode_code = endcore_shortcode_code + '[media_object style="right"';
                    endcore_shortcode_code = endcore_shortcode_code + ' aligned="'+jQuery('select[fieldname=media_aligned]').val()+'"]';
                    endcore_shortcode_code = endcore_shortcode_code + jQuery('input[fieldname=media_object]').val();
                    endcore_shortcode_code = endcore_shortcode_code + '[/media_object]';
                }

                endcore_shortcode_code = endcore_shortcode_code + '[/media]';
            } else if(endcore_shortcode_type == "tabs") {
                /**
                 * Tabs Shortcode
                 */
                var tab_id = Math.floor((Math.random() * 1000) + 1); /* Random Tab Number */
                var tab_titles = new Array();

                jQuery('#endcore-shortcodes-form-tab_tabs input[fieldname="label"]').each(function(){
                    tab_titles.push(jQuery(this).val());
                });
                tab_titles = jQuery.grep(tab_titles,function(n){ return(n) });
                tab_titles.toString();

                endcore_shortcode_code = endcore_shortcode_code + '[tabs style="tab" id="' + tab_id + '" title="' + tab_titles + '"]';
                var i=1;
                jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' input').each(function() {
                    if(jQuery(this).val() != "") {
                        endcore_shortcode_code = endcore_shortcode_code + '[tab id="' + i + '" tid="' + tab_id + '"]' + jQuery(this).parent('td').find('textarea').val() + '[/tab]';
                        i++;
                    }

                });
                endcore_shortcode_code = endcore_shortcode_code + '[/'+ endcore_shortcode_type + ']';
            } else if(endcore_shortcode_type == "accordion") {
                /**
                 * Accordion Shortcode
                 */
                var toggle_id = Math.floor((Math.random() * 1000) + 1);

                endcore_shortcode_code = endcore_shortcode_code + '[accordiongroup id="'+ toggle_id + '"]';
                jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' tr.toggle-items').each(function() {
                    if(jQuery(this).find('textarea').val() != "") {
                        endcore_shortcode_code = endcore_shortcode_code + '[accordion id="' + toggle_id + '" title="' + jQuery(this).find('input[fieldname="label"]').val() + '" active="' + jQuery(this).find('select').val() + '"]' + jQuery(this).find('textarea').val() + '[/accordion]';

                    }
                });

                endcore_shortcode_code = endcore_shortcode_code + '[/accordiongroup]';
            } else if(endcore_shortcode_type == "responsive") {
                /**
                 * Responsive Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '['+ jQuery(this).parent().parent().find('select[fieldname=aktion]').val();
                jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' input, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' select, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {
                    if(jQuery(this).attr('fieldname') != "" && jQuery(this).attr('fieldname') != undefined && jQuery(this).attr('fieldname') != "aktion" && jQuery(this).attr('fieldname') != "responsive_content") {
                        endcore_shortcode_code = endcore_shortcode_code + ' ' + jQuery(this).attr('fieldname') + '="' + jQuery(this).val() + '"';
                    }
                });
                endcore_shortcode_code = endcore_shortcode_code +  ']' + jQuery('textarea[fieldname=responsive_content]').val() + '[/'+ jQuery(this).parent().parent().find('select[fieldname=aktion]').val() +']';

            } else if(endcore_shortcode_type == "blogposts") {
                /**
                 * Blogposts Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[blogposts ';


                jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' input, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' select, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {

                    if(jQuery(this).attr('fieldname') != "" && jQuery(this).attr('fieldname') != undefined) {
                        if(jQuery(this).val() !== undefined && jQuery(this).val() !== "" && jQuery(this).val() !== null) {
                            endcore_shortcode_code = endcore_shortcode_code + ' ' + jQuery(this).attr('fieldname') + '="' + jQuery(this).val() + '"';
                        }
                    }

                });
                endcore_shortcode_code = endcore_shortcode_code + ']';
            } else if(endcore_shortcode_type == "taxonomyimages") {
                /**
                 * Taxonomy List Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[taxonomy_images ';


                jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' input, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' select, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {

                    if(jQuery(this).attr('fieldname') != "" && jQuery(this).attr('fieldname') != undefined) {
                        if(jQuery(this).val() !== undefined && jQuery(this).val() !== "" && jQuery(this).val() !== null) {
                            endcore_shortcode_code = endcore_shortcode_code + ' ' + jQuery(this).attr('fieldname') + '="' + jQuery(this).val() + '"';
                        }
                    }

                });
                endcore_shortcode_code = endcore_shortcode_code + ']';
            } else if(endcore_shortcode_type == "profiles") {
                /**
                 * Profile Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[profiles ';


                jQuery('#endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' input, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' select, #endcore-shortcodes-form-tab_' + endcore_shortcode_type + ' textarea').each(function() {

                    if(jQuery(this).attr('fieldname') != "" && jQuery(this).attr('fieldname') != undefined) {
                        if(jQuery(this).val() !== undefined && jQuery(this).val() !== "" && jQuery(this).val() !== null) {
                            endcore_shortcode_code = endcore_shortcode_code + ' ' + jQuery(this).attr('fieldname') + '="' + jQuery(this).val() + '"';
                        }
                    }

                });
                endcore_shortcode_code = endcore_shortcode_code + ']';
            } else if(endcore_shortcode_type == "signup_form") {
                /**
                 * Anmeldeformular Shortcode
                 */
                endcore_shortcode_code = endcore_shortcode_code + '[signup_form ';

                endcore_shortcode_code = endcore_shortcode_code + ' /]';
            }

            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, endcore_shortcode_code);
            tb_remove();

            return false;
        });


        /*
         * Höhe des Shortcode Generators anpassen
         */
        function set_shortcode_window_height() {
            var shortcode_window = jQuery('#TB_window .endcore-shortcodes-form-fields');
            var tb_window = jQuery('#TB_window');
            var new_height = tb_window.height() - 130;

            shortcode_window.height(new_height + 'px');
        }

        jQuery(window).resize(function() {
            set_shortcode_window_height();
        });

        set_shortcode_window_height();
    </script>

    <?php
    exit();
}