<?php
function at_import_big7_panel() {
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
            }
        endif;
    endif;

    $import = new AT_Import_Big7_Crawler();
    $cronjobs = new AT_Import_Cron();
    $amateurs = $import->getAmateurs();
    ?>

    <div class="ajax-loader">
        <div class="inner">
            <p></p>

            <div class="progress">
                <div class="progress-bar" style="width: 0%;">0%</div>
            </div>
        </div>
    </div>

    <div class="wrap" id="at-import-page-wrap">
		<div id="at-import-page-header">
			<h1><?php _e('Import &rsaquo; Big7', 'amateurtheme'); ?></h1>
			<h2 class="nav-tab-wrapper at-import-tabs-nav">
				<a class="nav-tab nav-tab-active" id="settings-tab" href="#top#settings"><?php _e('Einstellungen', 'amateurtheme'); ?></a>
				<a class="nav-tab" id="amateurs-tab" href="#top#amateurs"><?php _e('Amateure', 'amateurtheme'); ?></a>
				<a class="nav-tab" id="videos-tab" href="#top#videos"><?php _e('Videos', 'amateurtheme'); ?></a>
				<a class="nav-tab" id="topvideos-tab" href="#top#topvideos"><?php _e('Top Videos', 'amateurtheme'); ?></a>
				<a class="nav-tab" id="categories-tab" href="#top#categories"><?php _e('Kategorien', 'amateurtheme'); ?></a>
				<a class="nav-tab" id="apilog-tab" href="#top#apilog"><?php _e('API Log', 'amateurtheme'); ?></a>
			</h2>
		</div>

        <div id="at-import-tabs">
            <div class="at-import-tabs-content">
                <!-- START: Settings Tab-->
                <div id="settings" class="at-import-tab active">
                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span><?php _e('Einstellungen', 'amateurtheme'); ?></span></h3>
                        <div class="inside">
                            <form method="post">
                                <div class="form-group">
                                    <label for="at_big7_wmb"><?php _e('Webmaster ID', 'amateurtheme'); ?></label>
                                    <input name="at_big7_wmb" id="at_big7_wmb" type="text" value="<?php echo (get_option('at_big7_wmb') ? get_option('at_big7_wmb') : '') ?>" class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <label for="at_big7_post_status"><?php _e('Status für neue Videos', 'amateurtheme'); ?></label>
                                    <select name="at_big7_post_status" class="form-control">
                                        <option value="publish" <?php echo (get_option('at_big7_post_status') == 'publish' ? 'selected' : ''); ?>><?php _e('Veröffentlicht', 'amateurtheme'); ?></option>
                                        <option value="draft" <?php echo (get_option('at_big7_post_status') == 'draft' ? 'selected' : ''); ?>><?php _e('Entwurf', 'amateurtheme'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="at_big7_fsk18"><?php _e('FSK 18 Inhalte', 'amateurtheme'); ?></label>
                                    <select name="at_big7_fsk18" class="form-control">
                                        <option value="0" <?php echo (get_option('at_big7_fsk18') == '0' ? 'selected' : ''); ?>><?php _e('Nein', 'amateurtheme'); ?></option>
                                        <option value="1" <?php echo (get_option('at_big7_fsk18') == '1' ? 'selected' : ''); ?>><?php _e('Ja', 'amateurtheme'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="at_big7_video_description"><?php _e('Beschreibung Importieren', 'amateurtheme'); ?></label>
                                    <select name="at_big7_video_description" class="form-control">
                                        <option value="0" <?php echo (get_option('at_big7_video_description') == '0' ? 'selected' : ''); ?>><?php _e('Nein', 'amateurtheme'); ?></option>
                                        <option value="1" <?php echo (get_option('at_big7_video_description') == '1' ? 'selected' : ''); ?>><?php _e('Ja', 'amateurtheme'); ?></option>
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
                            <?php _e('Bitte achte darauf, dass du eine <strong>korrekte Webmaster ID</strong> angegeben hast!', 'amateurtheme'); ?>
                        </p>
                    </div>
                </div>
                <!-- END: Settings Tab-->

                <!-- START: Amateurs Tab-->
                <div id="amateurs" class="at-import-tab">
                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span><?php _e('Amateure', 'amateurtheme'); ?></span></h3>
                        <div class="inside">
                            <form method="post" class="form-inline at-cronjobs">
                                <table class="at-table at-import-table">
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
                                        'network' => 'big7',
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

                                            $amateur_videos = 0;
                                            if($amateurs) {
                                                foreach($amateurs as $c) {
                                                    if($c['u_id'] == $item->object_id) {
                                                        $amateur_videos = $c['videos'];
                                                    }
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td class="cron-name">
                                                    <a href="#" class="username-edit" data-user-id="<?php echo $item->object_id; ?>">
                                                        <?php echo $item->name; ?>
                                                    </a>
                                                </td>
                                                <td class="cron-video-count">
                                                    <?php echo $amateur_videos; ?>
                                                </td>
                                                <td class="cron-video-imported">
                                                    <?php echo at_import_big7_get_video_count($item->object_id); ?>
                                                </td>
                                                <td class="cron-last-update">
                                                    <?php echo $last_update; ?>
                                                </td>
                                                <td class="cron-action">
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

                                            if($amateurs) {
                                                ?>
                                                <select name="amateur" class="form-control at-amateur-select">
                                                    <option value=""><?php _e('Amateur auswählen', 'amateurtheme'); ?></option>
                                                    <?php
                                                    foreach($amateurs as $amateur) {
                                                        ?>
                                                        <option value="<?php echo $amateur['u_id']; ?>"><?php echo $amateur['nickname'] ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                            }
                                            ?>
                                            <input type="text" name="uid" id="uid" class="form-control" placeholder="<?php _e('User ID', 'amateurtheme'); ?>"/>
                                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php _e('Username', 'amateurtheme'); ?>"/>
                                            <input type="hidden" name="network" value="big7" />
                                            <input type="hidden" name="type" value="user" />
                                            <button name="submit" type="submit" class="btn btn-at"><?php _e('Amateur hinzufügen', 'amateurtheme'); ?></button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END: Amateurs Tab-->

                <!-- START: Videos Tab-->
                <div id="videos" class="at-import-tab">
                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span><?php _e('Videos', 'amateurtheme'); ?></span></h3>
                        <div class="inside">
                            <form method="post" id="at-get-videos">
                                <table class="at-table at-import-table">
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
                <!-- END: Videos Tab-->

                <!-- START: Top Videos Tab-->
                <div id="topvideos" class="at-import-tab">
                    <div class="metabox-holder postbox no-padding-top">
                        <h3 class="hndle"><span>TOP Videos</span></h3>
                        <div class="inside">
                            <form method="post" id="at-get-top-videos">
                                <button name="submit" type="submit" class="btn btn-at" style="float:left;"><?php _e('Videos abrufen', 'amateurtheme'); ?></button>
                                <div class="spinner" style="display:inline;float:left;"></div>
                                <div style="clear:both;"></div>
                            </form>
                        </div>
                    </div>

                    <div id="top-videos-wrapper">
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
                                    <span class="displaying-num  video-count"><span>0</span> Videos</span>
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
                                    <span class="displaying-num  video-count"><span>0</span> Videos</span>
                                </div>
                                <br class="clear">
                            </div>
                            <div class="clear"></div>
                        </form>
                        <div class="clear"></div>
                    </div>
                </div>
                <!-- END: Top Videos Tab-->

                <!-- START: Categories Tab-->
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
                                <table class="at-table at-import-table">
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
                                        'network' => 'big7',
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
                                                    <?php echo at_import_big7_get_video_count($item->object_id); ?>
                                                </td>
                                                <td class="cron-video-imported">
                                                    <?php echo at_import_big7_get_video_count($item->object_id, true); ?>
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
                                            <input type="hidden" name="network" value="big7" />
                                            <input type="hidden" name="type" value="category" />
                                            <button name="submit" type="submit" class="btn btn-at"><?php _e('Kategorie hinzufügen', 'amateurtheme'); ?></button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END: Categories Tab-->

                <!-- START: API Log Tab-->
                <div id="apilog" class="at-import-tab">
                    <div id="at-import-settings" class="metabox-holder postbox">
                        <h3 class="hndle"><span><?php _e('API Log', 'amateurtheme'); ?></span></h3>
                        <div class="inside">
                            <p><?php _e('Hier werden dir die letzten 200 Einträge der API log angezeigt.', 'amateurtheme'); ?></p>
                            <p><a href="" class="clear-api-log button" data-type="big7"><?php _e('Log löschen', 'amateurtheme'); ?></a></p>
                            <table class="at-table apilog">
                                <thead>
                                <tr>
                                    <th><?php _e('Datum', 'amateurtheme') ?></th>
                                    <th><?php _e('Typ', 'amateurtheme') ?></th>
                                    <th><?php _e('Nachricht', 'amateurtheme') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $log = get_option('at_big7_api_log');
                                if($log) {
                                    $log = array_reverse($log);

                                    foreach($log as $item) {
                                        ?>
                                        <tr>
                                            <td><?php echo date('d.m.Y H:i:s', $item['time']); ?></td>
                                            <td>
                                                <?php echo $item['post_id']; ?>
                                            </td>
                                            <td><?php echo $item['msg']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END: API Log Tab-->
            </div>
        </div>
    </div>
    <?php
}