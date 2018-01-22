<?php
/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 04.08.17
 * Time: 15:22
 */
class AT_Import_Big7_Crawler {
    public function __construct() {
        // folder
        $upload_dir = wp_upload_dir();
        $this->folder = $upload_dir['basedir'] . '/big7';

        $this->wmb = get_option('at_big7_wmb');
        $this->videos = 'http://cash.big7.com/xml_export.php?wmb=' . $this->wmb . '&security_key=c6aafc209e9593997dd949a85e15d49a&file=all_amateurs_videos&format=json&dl';
        $this->amateure = 'http://cash.big7.com/xml_export.php?wmb=' . $this->wmb . '&security_key=c6aafc209e9593997dd949a85e15d49a&file=all_amateurs&format=json&dl';

        $this->json_result = array();
    }

    function json_callback($item) {
        $this->json_result[] = $item;
    }

    function get($params = array()) {
        if($params['type'] == 'videos') {
            $url = $this->videos;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);

        if(curl_errno($curl)) {
            return 'Curl error: ' . curl_error($curl);
        }

        curl_close($curl);

        return $response;
    }

    function save($data, $filename = 'videos.json') {
        try {
            $fp = fopen($this->folder . '/' . $filename, 'w');
            fwrite($fp, $data);
            fclose($fp);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function read($filename = 'videos.json', $filter = array()) {
        if(!file_exists($this->folder . '/' . $filename)) {
            /**
             * Try to get the file from Big7 directly
             */
            if($filename == 'videos.json') {
                $data = file_get_contents($this->videos);
            } else {
                $data = file_get_contents($this->amateure);
            }

            if($data) {
                $this->save($data, $filename);
            }
        }

        $parser = new \JsonCollectionParser\Parser();
        $parser->parse($this->folder . '/' . $filename, [$this, 'json_callback'], true);

        $json_data = $this->json_result;

        if($json_data) {
            /**
             * @TODO: What about small Webspaces?
             */
            if($filter) {
                $filtered_data = array();

                foreach($filter as $key => $value) {
                    foreach($json_data as $k => $v) {
                        if($v[$key] == $value) {
                            $filtered_data[] = $v;
                        }
                    }
                }

                return $filtered_data;
            }

            return $json_data;
        }

        return false;
    }

    function getAmateurs() {
        $data = $this->read('amateure.json');

        if($data) {
            $amateurs = array();

            foreach($data as $k => $v) {
                if(!isset($v['anz_videos']) || $v['anz_videos'] === 0 || $v['anz_videos'] == '0') continue;

                $amateurs[] = array(
                    'u_id' => $v['u_id'],
                    'nickname' => $v['nickname'],
                    'videos' => $v['anz_videos']
                );
            }

            return $amateurs;
        }

        return false;
    }

    function getAmateur($id, $videos = false) {
        $data = $this->read('videos.json', array('u_id' => $id));

        if($data && !$videos) {
            unset($data[0]['videos']);
        }

        if(isset($data[0])) {
            return $data[0];
        }

        return false;
    }
}