<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 25.07.17
 * Time: 15:23
 */
class AT_Import_Cron {

    public function add($args) {
        global $wpdb;

        $check = $wpdb->get_row('SELECT id FROM ' . AT_CRON_TABLE . ' WHERE object_id = "' . $args['object_id'] . '" AND network = "' . $args['network'] . '"');

        if($check) {
            return false;
        }

        $wpdb->insert(
            AT_CRON_TABLE,
            array(
                'object_id' => $args['object_id'],
                'network' => $args['network'],
                'name' => $args['name'],
                'alias' => $args['alias'],
                'type' => $args['type'],
                'processing' => 0
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d'
            )
        );

        return true;
    }

    public function update($fields, $where) {
        global $wpdb;

        $wpdb->update(AT_CRON_TABLE, $fields, $where);

        return true;
    }

    public function delete($id) {
        global $wpdb;

        $wpdb->delete(AT_CRON_TABLE, array('id' => $id));

        return true;
    }

    public function get($args = array()) {
        global $wpdb;

        $query = 'SELECT * FROM ' . AT_CRON_TABLE;

        if(!empty($args)) {
            $where = ' WHERE ';

            $i=0;
            foreach($args as $key => $val) {
                if($i!=0) $where .= ' AND ';

                $where .= $key . ' = "' . $val . '" ';

                $i++;
            }

            $query .= $where;
        }


        $cronjobs = $wpdb->get_results($query);

        if($cronjobs) {
            return $cronjobs;
        }

        return false;
    }
}