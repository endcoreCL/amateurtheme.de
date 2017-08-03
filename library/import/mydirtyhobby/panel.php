<?php
function at_import_mydirtyhobby_panel() {
    if (get_magic_quotes_gpc()) {
        $_POST = array_map('stripslashes_deep', $_POST);
        $_GET = array_map('stripslashes_deep', $_GET);
        $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
        $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
    }

    if (is_array($_REQUEST)) :
        if (isset($_REQUEST['action'])) :
            if ($_REQUEST['action'] == 'save') echo '<div id="message" class="updated fade"><p><strong>Einstellungen gespeichert.</strong></p></div>';

            if ('save' == $_REQUEST['action']) {
                foreach ($_POST as $key => $value) {
                    if ($key != "action" && $key != "save") {
                        if ($value == "") {
                            update_option($key, '');
                        } else {
                            update_option($key, $value);
                        }
                    }
                }
            } else if ('reset' == $_REQUEST['action']) {
                foreach ($options as $value) {
                    delete_option($value['id']);
                }
            }
        endif;
    endif;

    $database = new AT_Import_MDH_DB();
    $cronjobs = new AT_Import_Cron();
    ?>

    <div class="ajax-loader">
        <div class="inner">
            <p></p>

            <div class="progress">
                <div class="progress-bar" style="width:0%;">0%</div>
            </div>
        </div>
    </div>

    <div class="wrap" id="at-import-page-wrap">
        <h1><?php _e('Import &rsaquo; MyDirtyHobby', 'amateurtheme'); ?></h1>

        <div id="checkConnection"></div>

        <div id="importStatus"></div>

        <div id="at-import-tabs">
            <h2 class="nav-tab-wrapper at-import-tabs-nav">
                <a class="nav-tab nav-tab-active" id="settings-tab" href="#top#settings"><?php _e('Einstellungen', 'amateurtheme'); ?></a>
                <a class="nav-tab" id="amateurs-tab" href="#top#amateurs"><?php _e('Amateure', 'amateurtheme'); ?></a>
                <a class="nav-tab" id="videos-tab" href="#top#videos"><?php _e('Videos', 'amateurtheme'); ?></a>
                <a class="nav-tab" id="categories-tab" href="#top#categories"><?php _e('Kategorien', 'amateurtheme'); ?></a>
            </h2>

            <div class="at-import-tabs-content">
                <div id="settings" class="at-import-tab active">
                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span><?php _e('Einstellungen', 'amateurtheme'); ?></span></h3>
                        <div class="inside">
                            <form method="post">
                                <div class="form-group">
                                    <label for="at_mdh_naffcode"><?php _e('Naffcode', 'amateurtheme'); ?></label>
                                    <input name="at_mdh_naffcode" id="at_mdh_naffcode" type="text" value="<?php echo (get_option('at_mdh_naffcode') ? get_option('at_mdh_naffcode') : '') ?>" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <label for="at_mdh_post_status"><?php _e('Status für neue Videos', 'amateurtheme'); ?></label>
                                    <select name="at_mdh_post_status" class="form-control">
                                        <option value="publish" <?php echo (get_option('at_mdh_post_status') == 'publish' ? 'selected' : ''); ?>><?php _e('Veröffentlicht', 'amateurtheme'); ?></option>
                                        <option value="draft" <?php echo (get_option('at_mdh_post_status') == 'draft' ? 'selected' : ''); ?>><?php _e('Entwurf', 'amateurtheme'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="at_mdh_fsk18"><?php _e('FSK 18 Bilder', 'amateurtheme'); ?></label>
                                    <select name="at_mdh_fsk18" class="form-control">
                                        <option value="0" <?php echo (get_option('at_mdh_fsk18') == '0' ? 'selected' : ''); ?>><?php _e('Nein', 'amateurtheme'); ?></option>
                                        <option value="1" <?php echo (get_option('at_mdh_fsk18') == '1' ? 'selected' : ''); ?>><?php _e('Ja', 'amateurtheme'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="at_mdh_video_description"><?php _e('Beschreibung Importieren', 'amateurtheme'); ?></label>
                                    <select name="at_mdh_video_description" class="form-control">
                                        <option value="0" <?php echo (get_option('at_mdh_video_description') == '0' ? 'selected' : ''); ?>><?php _e('Nein', 'amateurtheme'); ?></option>
                                        <option value="1" <?php echo (get_option('at_mdh_video_description') == '1' ? 'selected' : ''); ?>><?php _e('Ja', 'amateurtheme'); ?></option>
                                    </select>
                                    <p class="hint"><?php _e('Jedes Video hat eine kurze Beschreibung die auch importiert werden kann. Achte aber bitte darauf das diese Texte ggf. Duplicate Content erzeugen können!', 'amateurtheme'); ?></p>
                                </div>

                                <input type="hidden" name="action" value="save"/>
                                <input name="save" type="submit" class="btn btn-at" value="<?php _e('Speichern', 'amateurtheme'); ?>"/>
                            </form>
                        </div>
                    </div>

                    <div class="info" style="display:block;margin-bottom:10px;margin-top:0px;margin-right:0;">
                        <p>
                            <strong><?php _e('Hinweise:', 'amateurtheme'); ?></strong>
                        </p>

                        <p>
                            <?php _e('Bitte achte darauf, dass du einen <strong>korrekten Naffcode</strong> angegeben hast!', 'amateurtheme'); ?>
                        </p>
                    </div>
                </div>

                <div id="amateurs" class="at-import-tab">
                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span><?php _e('Amateure', 'amateurtheme'); ?></span></h3>
                        <div class="inside">
                            <form method="post" class="form-inline at-cronjobs">
                                <table class="at-import-table">
                                    <thead>
                                    <tr>
                                        <th><?php _e('User', 'amateurtheme'); ?></th>
                                        <th><?php _e('Videos (gesamt)', 'amateurtheme'); ?></th>
                                        <th><?php _e('Importierte Videos', 'amateurtheme'); ?></th>
                                        <th><?php _e('Zuletzt akutalisiert', 'amateurtheme'); ?></th>
                                        <th><?php _e('Aktion', 'amateurtheme'); ?></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $args = array(
                                            'network' => 'mydirtyhobby',
                                            'type' => 'user'
                                        );
                                        $amateure = $cronjobs->get($args);
                                        if ($amateure) :
                                            foreach($amateure as $item) :
                                                $last_update = new DateTime($item->last_activity);
                                                if(checkdate($last_update->format('m'), $last_update->format('d'), $last_update->format('Y'))) :
                                                    $last_update = $last_update->format('d.m.Y H:i:s');
                                                else :
                                                    $last_update = '-';
                                                endif;
                                                ?>
                                                <tr>
                                                    <td class="cron-name">
                                                        <a href="#" class="username-edit" data-user-id="<?php echo $item->object_id; ?>">
                                                            <?php echo $item->name; ?>
                                                        </a>
                                                    </td>
                                                    <td class="cron-video-count">
                                                        <?php echo at_import_mdh_get_video_count($item->object_id); ?>
                                                    </td>
                                                    <td class="cron-video-imported">
                                                        <?php echo at_import_mdh_get_video_count($item->object_id, true); ?>
                                                    </td>
                                                    <td class="cron-last-update">
                                                        <?php echo $last_update; ?>
                                                    </td>
                                                    <td class="cron-action">
                                                        <?php if($item->scrape == 0) : ?>
                                                            <a href="#" class="cron-update" data-id="<?php echo $item->id; ?>" data-field="scrape" data-value="1"><?php _e('Scrape aktivieren', 'amateurtheme'); ?></a>
                                                        <?php else: ?>
                                                            <a href="#" class="cron-update" data-id="<?php echo $item->id; ?>" data-field="scrape" data-value="0"><?php _e('Scrape deaktivieren', 'amateurtheme'); ?></a>
                                                        <?php endif; ?>
                                                        |
                                                        <?php if($item->import == 0) : ?>
                                                            <a href="#" class="cron-update" data-id="<?php echo $item->id; ?>" data-field="import" data-value="1"><?php _e('Import aktivieren', 'amateurtheme'); ?></a>
                                                        <?php else: ?>
                                                            <a href="#" class="cron-update" data-id="<?php echo $item->id; ?>" data-field="import" data-value="0"><?php _e('Import deaktivieren', 'amateurtheme'); ?></a>
                                                        <?php endif; ?>
                                                        |
                                                        <a href="#" class="cron-delete" data-id="<?php echo $item->id; ?>"><?php _e('löschen', 'amateurtheme'); ?></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <td colspan="6">
                                            <?php
                                            $amateurs_dropdown = $database->amateurs_dropdown();
                                            if($amateurs_dropdown) {
                                                ?>
                                                <select name="amateur" class="form-control at-amateur-select">
                                                    <option value=""><?php _e('Amateur auswählen', 'amateurtheme'); ?></option>
                                                    <?php echo $amateurs_dropdown; ?>
                                                </select>
                                                <?php
                                            }
                                            ?>
                                            <input type="text" name="uid" id="uid" class="form-control" placeholder="<?php _e('User ID', 'amateurtheme'); ?>"/>
                                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php _e('Username', 'amateurtheme'); ?>"/>
                                            <input type="hidden" name="network" value="mydirtyhobby" />
                                            <input type="hidden" name="type" value="user" />
                                            <button name="submit" type="submit" class="btn btn-at"><?php _e('Amateur hinzufügen', 'amateurtheme'); ?></button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>

                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span><?php _e('Hilfsmittel', 'amateurtheme'); ?></span></h3>
                        <div class="inside">
                            <div class="at-crawl-amateurs">
                                <button type="button" data-action="crawl-amateurs" class="btn btn-at at-mdh-amateur-dropdown"><?php _e('Liste der Amateure aktualisieren/generieren', 'amateurtheme'); ?></button>
                                <div class="status" style="display: none;">
                                    <p>
                                        <?php _e('Anzahl an Amateuren:', 'amateurtheme'); ?> <span class="total">0</span><br>
                                        <?php _e('Aktuelle Position:', 'amateurtheme'); ?> <span class="offset">0</span><br>
                                        <?php _e('Geschätzte Restzeit:', 'amateurtheme'); ?> <span class="time-remaining">0</span> <?php _e('Minute(n)', 'amateurtheme'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="videos" class="at-import-tab">
                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span><?php _e('Videos', 'amateurtheme'); ?></span></h3>
                        <div class="inside">
                            <form method="post" id="at-get-videos">
                                <table class="at-import-table">
                                    <tr>
                                        <td>
                                            <select name="u_id" id="u_id" class="form-control">
                                                <option><?php _e('Amateur auswählen', 'amateurtheme'); ?></option>
                                                <?php
                                                if ($amateure) :
                                                    foreach ($amateure as $item) :
                                                        ?>
                                                        <option value="<?php echo $item->object_id; ?>"><?php echo $item->name; ?></option>
                                                        <?php
                                                    endforeach;
                                                endif; ?>
                                            </select>
                                        </td>

                                        <td>
                                            <button name="submit" type="submit" class="btn btn-at" style="float:left;"><?php _e('Videos abrufen', 'amateurtheme'); ?></button>
                                            <div class="spinner" style="display:inline;float:left;"></div>
                                            <div style="clear:both;"></div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>

                    <div id="videos-wrapper">
                        <form id="posts-filter" method="post">
                            <div class="tablenav top">
                                <div class="alignleft actions bulkactions">
                                    <label for="bulk-action-selector-bottom" class="screen-reader-text"><?php _e('Mehrfachauswahl', 'amateurtheme'); ?></label>
                                    <select name="video_category" id="video_category">
                                        <option value="-1" selected="selected"><?php _e('Kategorie wählen', 'amateurtheme'); ?></option>
                                        <?php
                                        $video_category = get_terms('video_category', 'orderby=name&hide_empty=0');
                                        if ($video_category) {
                                            foreach ($video_category as $term) {
                                                ?>
                                                <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                    <select name="video_actor" id="video_actor">
                                        <option value="-1" selected="selected"><?php _e('Darsteller wählen', 'amateurtheme'); ?></option>
                                        <?php
                                        $video_actor = get_terms('video_actor', 'orderby=name&hide_empty=0');
                                        if ($video_actor) {
                                            foreach ($video_actor as $term) {
                                                ?>
                                                <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <button name="" class="start-import button"><?php _e('Ausgewählte Videos importieren', 'amateurtheme'); ?></button>
                                </div>
                                <div class="tablenav-pages one-page">
                                    <span class="displaying-num  video-count"><span>0</span> <?php _e('Videos', 'amateurtheme'); ?></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <table class="wp-list-table widefat fixed videos">
                                <colgroup>
                                    <col width="">
                                    <col width="10%">
                                    <col width="60%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                </colgroup>

                                <thead>
                                <tr>
                                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                                        <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Beschreibung', 'amateurtheme'); ?></label>
                                        <input id="cb-select-all-1" type="checkbox">
                                    </th>
                                    <th scope="col" id="image" class="manage-column column-image" style="">
                                        <span><?php _e('Vorschau', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="title" class="manage-column column-title" style="">
                                        <span><?php _e('Titel', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="duration" class="manage-column column-duration" style="">
                                        <span><?php _e('Länge', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="rating" class="manage-column column-duration" style="">
                                        <span><?php _e('Bewertung', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="description" class="manage-column column-description" style="display:none;">
                                        <span><?php _e('Beschreibung', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="time" class="manage-column column-time" style="">
                                        <span><?php _e('Datum', 'amateurtheme'); ?></span>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="the-list">
                                <tr>
                                    <td>-</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                                        <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Beschreibung', 'amateurtheme'); ?></label>
                                        <input id="cb-select-all-1" type="checkbox">
                                    </th>
                                    <th scope="col" id="image" class="manage-column column-image" style="width:100px;">
                                        <span><?php _e('Vorschau', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="title" class="manage-column column-title" style="">
                                        <span><?php _e('Titel', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="duration" class="manage-column column-duration" style="">
                                        <span><?php _e('Länge', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="rating" class="manage-column column-duration" style="">
                                        <span><?php _e('Bewertung', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="description" class="manage-column column-description" style="display:none;">
                                        <span><?php _e('Beschreibung', 'amateurtheme'); ?></span>
                                    </th>
                                    <th scope="col" id="time" class="manage-column column-time" style="">
                                        <span><?php _e('Datum', 'amateurtheme'); ?></span>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="tablenav bottom">
                                <div class="alignleft actions bulkactions">
                                    <label for="bulk-action-selector-bottom" class="screen-reader-text"><?php _e('Mehrfachauswahl', 'amateurtheme'); ?></label>
                                    <select name="video_category" id="video_category">
                                        <option value="-1" selected="selected"><?php _e('Kategorie wählen', 'amateurtheme'); ?></option>
                                        <?php
                                        $video_category = get_terms('video_category', 'orderby=name&hide_empty=0');
                                        if ($video_category) {
                                            foreach ($video_category as $term) {
                                                ?>
                                                <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                    <select name="video_actor" id="video_actor">
                                        <option value="-1" selected="selected"><?php _e('Darsteller wählen', 'amateurtheme'); ?></option>
                                        <?php
                                        $video_actor = get_terms('video_actor', 'orderby=name&hide_empty=0');
                                        if ($video_actor) {
                                            foreach ($video_actor as $term) {
                                                ?>
                                                <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <button name="" class="start-import button"><?php _e('Ausgewählte Videos importieren', 'amateurtheme'); ?></button>
                                </div>
                                <div class="tablenav-pages one-page">
                                    <span class="displaying-num  video-count"><span>0</span> <?php _e('Videos', 'amateurtheme'); ?></span>
                                </div>
                                <br class="clear">
                            </div>
                            <div class="clear"></div>
                        </form>
                        <div class="clear"></div>
                    </div>
                </div>

                <div id="topvids" class="at-import-tab">
                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span>TOP Videos</span></h3>
                        <div class="inside">
                            <form method="post" id="vi-get-top-videos">
                                <button name="submit" type="submit" class="btn btn-at" style="float:left;">Videos abrufen
                                </button>
                                <div class="spinner" style="display:inline;float:left;"></div>
                                <div style="clear:both;"></div>
                            </form>
                        </div>
                    </div>

                    <div id="topvideos">
                        <form id="posts-filter" method="post">
                            <div class="tablenav top">
                                <div class="alignleft actions bulkactions">
                                    <label for="bulk-action-selector-bottom"
                                           class="screen-reader-text">Mehrfachauswahl</label>
                                    <button name="" class="start-top-import button">Ausgewählte Videos importieren</button>
                                </div>
                                <div class="tablenav-pages one-page">
                                    <span class="displaying-num  video-count"><span>0</span> Videos</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <table class="wp-list-table widefat fixed videos">
                                <colgroup>
                                    <col width="">
                                    <col width="10%">
                                    <col width="20%">
                                    <col width="40%">
                                    <col width="10%">
                                    <col width="10%">
                                    <col width="10%">
                                </colgroup>

                                <thead>
                                <tr>
                                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                                        <label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label><input
                                                id="cb-select-all-1" type="checkbox">
                                    </th>
                                    <th scope="col" id="image" class="manage-column column-image" style="">
                                        <span>Vorschau</span>
                                    </th>
                                    <th scope="col" id="amateur" class="manage-column column-amateur" style="">
                                        <span>Amateur</span>
                                    </th>
                                    <th scope="col" id="title" class="manage-column column-title" style="">
                                        <span>Titel</span>
                                    </th>
                                    <th scope="col" id="duration" class="manage-column column-duration" style="">
                                        <span>Länge</span>
                                    </th>
                                    <th scope="col" id="rating" class="manage-column column-duration" style="">
                                        <span>Bewertung</span>
                                    </th>
                                    <th scope="col" id="description" class="manage-column column-description"
                                        style="display:none;">
                                        <span>Beschreibung</span>
                                    </th>
                                    <th scope="col" id="time" class="manage-column column-time" style="">
                                        <span>Datum</span>
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="the-list">
                                <tr>
                                    <td>-</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                                        <label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label><input
                                                id="cb-select-all-1" type="checkbox">
                                    </th>
                                    <th scope="col" id="image" class="manage-column column-image" style="width:100px;">
                                        <span>Vorschau</span>
                                    </th>
                                    <th scope="col" id="amateur" class="manage-column column-amateur" style="">
                                        <span>Amateur</span>
                                    </th>
                                    <th scope="col" id="title" class="manage-column column-title" style="">
                                        <span>Titel</span>
                                    </th>
                                    <th scope="col" id="duration" class="manage-column column-duration" style="">
                                        <span>Länge</span>
                                    </th>
                                    <th scope="col" id="rating" class="manage-column column-duration" style="">
                                        <span>Bewertung</span>
                                    </th>
                                    <th scope="col" id="description" class="manage-column column-description"
                                        style="display:none;">
                                        <span>Beschreibung</span>
                                    </th>
                                    <th scope="col" id="time" class="manage-column column-time" style="">
                                        <span>Datum</span>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="tablenav bottom">
                                <div class="alignleft actions bulkactions">
                                    <label for="bulk-action-selector-bottom"
                                           class="screen-reader-text">Mehrfachauswahl</label>
                                    <button name="" class="start-top-import button">Ausgewählte Videos importieren</button>
                                </div>
                                <div class="tablenav-pages one-page">
                                    <span class="displaying-num  video-count"><span>0</span> Videos</span>
                                </div>
                                <br class="clear">
                            </div>
                            <div class="clear"></div>
                        </form>
                        <div class="clear"></div>
                    </div>
                </div>

                <div id="categories" class="at-import-tab">
                    <div class="update-nag" style="display:block;margin-bottom:10px;margin-top:0px;margin-right:0;">
                        <p>
                            <strong><?php _e('Vorsicht:', 'amateurtheme'); ?></strong>
                        </p>

                        <p>
                            <?php _e('Der Kategorie Import ist nur für leistungsstarke Server geeignet! Bitte genieße dieses Feature
                            mit Vorsicht', 'amateurtheme'); ?>
                        </p>
                    </div>

                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span><?php _e('Kategorien', 'amateurtheme'); ?></span></h3>

                        <div class="inside">
                            <form method="post" class="form-inline at-cronjobs">
                                <table class="at-import-table">
                                    <thead>
                                    <tr>
                                        <th><?php _e('Name', 'amateurtheme'); ?></th>
                                        <th><?php _e('Videos (gesamt)', 'amateurtheme'); ?></th>
                                        <th><?php _e('Importierte Videos', 'amateurtheme'); ?></th>
                                        <th><?php _e('Zuletzt akutalisiert', 'amateurtheme'); ?></th>
                                        <th><?php _e('Aktion', 'amateurtheme'); ?></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    $args = array(
                                        'network' => 'mydirtyhobby',
                                        'type' => 'category'
                                    );
                                    $categories = $cronjobs->get($args);
                                    if ($categories) :
                                        foreach ($categories as $item) :
                                            $last_update = new DateTime($item->last_activity);
                                            if(checkdate($last_update->format('m'), $last_update->format('d'), $last_update->format('Y'))) :
                                                $last_update = $last_update->format('d.m.Y H:i:s');
                                            else :
                                                $last_update = '-';
                                            endif;
                                            ?>
                                            <tr>
                                                <td class="cron-name">
                                                    <a href="#" class="username-edit" data-user-id="<?php echo $item->object_id; ?>">
                                                        <?php echo $item->name; ?>
                                                    </a>
                                                </td>
                                                <td class="cron-video-count">
                                                    <?php echo at_import_mdh_get_video_count($item->object_id); ?>
                                                </td>
                                                <td class="cron-video-imported">
                                                    <?php echo at_import_mdh_get_video_count($item->object_id, true); ?>
                                                </td>
                                                <td class="cron-last-update">
                                                    <?php echo $last_update; ?>
                                                </td>
                                                <td class="cron-action">
                                                    <?php if($item->scrape == 0) : ?>
                                                        <a href="#" class="cron-update" data-id="<?php echo $item->id; ?>" data-field="scrape" data-value="1"><?php _e('Scrape aktivieren', 'amateurtheme'); ?></a>
                                                    <?php else: ?>
                                                        <a href="#" class="cron-update" data-id="<?php echo $item->id; ?>" data-field="scrape" data-value="0"><?php _e('Scrape deaktivieren', 'amateurtheme'); ?></a>
                                                    <?php endif; ?>
                                                    |
                                                    <?php if($item->import == 0) : ?>
                                                        <a href="#" class="cron-update" data-id="<?php echo $item->id; ?>" data-field="import" data-value="1"><?php _e('Import aktivieren', 'amateurtheme'); ?></a>
                                                    <?php else: ?>
                                                        <a href="#" class="cron-update" data-id="<?php echo $item->id; ?>" data-field="import" data-value="0"><?php _e('Import deaktivieren', 'amateurtheme'); ?></a>
                                                    <?php endif; ?>
                                                    |
                                                    <a href="#" class="cron-delete" data-id="<?php echo $item->id; ?>"><?php _e('löschen', 'amateurtheme'); ?></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                    <?php endif; ?>
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <td><input name="catid" id="catid" class="form-control" placeholder="ID"/> <a href="#" id="new-catslug-help"><i class="fa fa-question-circle"></i></a</td>
                                        <td><input name="catname" id="catname" class="form-control" placeholder="Name der Kategorie"/></td>
                                        <td colspan="4">
                                            <input type="hidden" name="network" value="mydirtyhobby" />
                                            <input type="hidden" name="type" value="category" />
                                            <button name="submit" type="submit" class="btn btn-at"><?php _e('Kategorie hinzufügen', 'amateurtheme'); ?></button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>
                    <div class="info" style="display:block;margin-bottom:10px;margin-top:0px;margin-right:0;">
                        <p>
                            <strong>Hinweise:</strong>
                        </p>

                        <p>
                            <?php date_default_timezone_set('Europe/Berlin');
                            $timestamp = wp_next_scheduled('mdh_scrape_cat_videos'); ?>
                            Die Videos einer Kategorie werden stetig automatisch aktualisiert, das passiert jede halbe
                            Stunde. Vorraussetzung hierfür ist, das für die Kategorie das Scrapen aktiviert wurde.<br><br>
                            Nächster Start am: <strong><?php echo date('d.m.Y', $timestamp); ?></strong> um
                            <strong><?php echo date('H:i:s', $timestamp); ?> Uhr</strong>
                        </p>
                        <hr>
                        <p>
                            <?php $timestamp = wp_next_scheduled('mdh_import_cat_video'); ?>
                            Sobald du den Import aktivierst, werden alle verfügbaren Videos <u>automatisch</u> in die
                            WordPress Datenbank geschrieben. Ein Script hierfür läuft automatisch alle 10 Minuten.<br><br>
                            Nächster Start am: <strong><?php echo date('d.m.Y', $timestamp); ?></strong> um
                            <strong><?php echo date('H:i:s', $timestamp); ?> Uhr</strong>
                        </p>
                        <hr>
                        <p>
                            Aktuelle Serverzeit: <strong><?php echo date('d.m.Y H:i:s'); ?> Uhr</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    <script type="text/javascript">

        /*
         * IMPORT STATUS

        var getImportStatus = function () {
            jQuery.get(ajaxurl + '?&action=vi_mdh_get_current_import_status').done(function (data) {
                if (data) {
                    jQuery('#importStatus').html(data);
                }
            });
        }

        window.setInterval(function () {
            getImportStatus();
        }, 10000); */



        /*
         * TOP VIDEOS
         */
        jQuery('#vi-get-top-videos').submit(function (e) {
            var loader = jQuery(this).find('.spinner');

            if (loader.hasClass('is-active')) {
                return;
            }

            loader.addClass('is-active');

            jQuery.get(ajaxurl + '?&action=vi_mdh_topitems_get_videos', jQuery(this).serialize()).done(function (data) {
                var target = jQuery('#topvideos table tbody');

                if (data != "[]") {
                    var json = JSON.parse(data);

                    if (json.status == 'error') {
                        jQuery(target).html('<tr class="error"><th scope="row" class="check-column"><input type="checkbox" id="cb-select-0" name="video[]" value="0" disabled></th><td colspan="6">FEHLER: Die Verbindung zu MDH konnte nicht hergestellt werden.</td></tr>')
                    } else if (json.status == 'too many connections') {
                        jQuery(target).html('<tr class="error"><th scope="row" class="check-column"><input type="checkbox" id="cb-select-0" name="video[]" value="0" disabled></th><td colspan="6">FEHLER: Die Verbindung zu MDH wurde aufgrund zu vieler Verbindung temporär gesperrt. Bitte warte einen Moment oder wende dich an den Support von MyDirtyHobby.</td></tr>')
                    } else {
                        var total = json.total;
                        var items = json.items;

                        jQuery(target).html('');

                        jQuery.each(items, function (i, item) {
                            if (item.imported == 'true') {
                                var html = '<tr id="video-' + item.id + '" class="video video-' + item.id + ' success imported">';
                                html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-' + item.id + '" name="video[]" value="' + item.id + '" disabled></th>';
                            } else {
                                var html = '<tr id="video-' + item.id + '" class="video video-' + item.id + '">';
                                html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-' + item.id + '" name="video[]" value="' + item.id + '"></th>';
                            }

                            html += '<td class="image"><img src="' + item.image + '" alt="" style="max-width:60px;"/></td>';
                            html += '<td class="amateur">' + item.username + '</td>';
                            html += '<td class="title">' + item.title + '</td>';
                            html += '<td class="duration">' + item.duration + '</td>';
                            html += '<td class="rating">' + item.rating + '</td>';
                            html += '<td class="description" style="display:none;">' + item.description + '</td>';
                            html += '<td class="time">' + item.time + '</td>';
                            html += '</tr>';

                            jQuery(target).append(html);
                        });

                        jQuery('.tablenav .video-count span').html(total);
                    }
                } else {
                    jQuery(target).html('<tr><th scope="row" class="check-column"><input type="checkbox" id="cb-select-0" name="video[]" value="0" disabled></th><td colspan="6">Es wurden keine (neuen) Videos gefunden.</td></tr>')
                }
            }).always(function () {
                loader.removeClass('is-active');
            });
            e.preventDefault();
        });

        jQuery('.start-top-import').bind('click', function (e) {
            var current_button = this;
            var max_videos = jQuery('#topvideos tbody tr:not(.success)').find('input[type="checkbox"]:checked').length;
            var ajax_loader = jQuery('.ajax-loader');
            var i = 1;

            if (max_videos != "0") {
                jQuery(ajax_loader).addClass('active').find('p').html('Importiere Video <span class="current">1</span> von ' + max_videos);

                jQuery('#topvideos tbody tr').find('input[type="checkbox"]:checked').each(function () {
                    var current = jQuery(this).closest('tr');

                    var id = jQuery(this).val();
                    var image = jQuery(current).find('.image img').attr('src');
                    var darsteller = jQuery(current).find('.amateur').html();
                    var title = jQuery(current).find('.title').html();
                    var duration = jQuery(current).find('.duration').html();
                    var rating = jQuery(current).find('.rating').html();
                    var description = jQuery(current).find('.description').html();
                    var time = jQuery(current).find('.time').html();

                    var video = {
                        id: id,
                        image: image,
                        title: title,
                        duration: duration,
                        rating: rating,
                        description: description,
                        time: time,
                        kategorie: '-1',
                        darsteller: darsteller
                    };

                    var xhr = jQuery.post(ajaxurl + '?action=vi_mdh_import_video', video).done(function (data) {
                        if (data != "error") {
                            jQuery('#topvideos table tr#video-' + data).addClass('success');
                            jQuery('#topvideos table tr#video-' + data).find('input[type=checkbox]').attr('checked', false).attr('disabled', true);
                        } else {
                            jQuery('#topvideos table tr#video-' + data).addClass('error');
                        }
                    }).success(function () {
                        var procentual = (100 / max_videos) * i;
                        var procentual_fixed = procentual.toFixed(2);
                        jQuery(ajax_loader).find('.current').html(i);
                        jQuery(ajax_loader).find('.progress-bar').css('width', procentual + '%').html(procentual_fixed + '%');

                        if (i >= max_videos) {
                            jQuery(ajax_loader).removeClass('active');
                        }

                        i++;
                    });

                });
            }

            e.preventDefault();
        });

        /*
         * KATEGORIEN
         */
        jQuery('#vi-new-cat').submit(function (e) {
            jQuery.get(ajaxurl + '?&action=vi_mdh_new_cat', jQuery(this).serialize()).done(function (data) {
                var response = JSON.parse(data);

                if (response.status != 'ok') {
                    jQuery('#vi-new-cat').append('<p>Fehler: Die Kategorie konnte nicht angelegt werden.</p>');
                    return false;
                } else {
                    location.reload();
                }
            });

            return false;
        });

        jQuery('#vi-new-cat .delete-cat').bind('click', function (e) {
            var target = jQuery(this);
            var catslug = jQuery(this).attr('data-slug');

            jQuery.get(ajaxurl + '?&action=vi_mdh_delete_cat', {catslug: catslug}).done(function (data) {
                var response = JSON.parse(data);

                if (response.status == 'ok') {
                    jQuery(target).closest('tr').fadeOut();
                }
            });

            e.preventDefault();
        });

        jQuery('#vi-new-cat .scrape-cat').bind('click', function (e) {
            var target = jQuery(this);
            var catslug = jQuery(this).attr('data-cat');
            var scrape = jQuery(this).attr('data-scrape');

            jQuery(this).after('<span class="spinner" style="visibility:initial"></span>');

            jQuery.get(ajaxurl + '?&action=vi_mdh_scrape_cat', {
                catslug: catslug,
                scrape: scrape
            }).done(function (data) {
                var response = JSON.parse(data);

                if (response.status == 'ok') {
                    location.reload();
                }
            });

            e.preventDefault();
        });

        jQuery('#vi-new-cat .import-cat').bind('click', function (e) {
            var target = jQuery(this);
            var catslug = jQuery(this).attr('data-cat');
            var import_status = jQuery(this).attr('data-import');

            jQuery(this).after('<span class="spinner" style="display:block"></span>');

            jQuery.get(ajaxurl + '?&action=vi_mdh_import_cat', {
                catslug: catslug,
                import_status: import_status
            }).done(function (data) {
                var response = JSON.parse(data);

                if (response.status == 'ok') {
                    location.reload();
                }
            });

            e.preventDefault();
        });
    </script>
    <?php
}