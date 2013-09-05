<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 05/08/13
 * Time: 10:38
 * To change this template use File | Settings | File Templates.
 */
class OpenCalais2
{

    public function getTags($licence, $content) {

        $licence = "4h756axxwfktmfpdpyqqj7d3";


//        $sendParams = String.Format('licenseID={0}&content={1}&paramsXML={2}', $m_Licence, HttpUtility.UrlEncode($m_Content), HttpUtility.HtmlDecode($m_CParams));

        $xmlParams = "";
        $xmlParams .= ('<c:params xmlns:c="http://s.opencalais.com/1/pred/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">');
        $xmlParams .= ('<c:processingDirectives c:contentType="text/txt" c:enableMetadataType="GenericRelations,SocialTags" c:omitOutputtingOriginalText="TRUE" c:outputFormat="xml/rdf" c:docRDFaccesible="false" c:calculateRelevanceScore="true">');
        $xmlParams .= ('</c:processingDirectives>');
        $xmlParams .= ('<c:userDirectives c:allowDistribution="false" c:allowSearch="false" c:externalID="17cabs901" c:submitter="ABC">');
        $xmlParams .= ('</c:userDirectives>');
        $xmlParams .= ('</c:params>');

        $fields = array(
            'licenseID' => $licence,
            'content' => urlencode($content),
            'paramsXML' => html_entity_decode($xmlParams)
        );

        $response = $this::postToURL("http://api.opencalais.com/enlighten/calais.asmx/Enlighten", $fields);

//        $responseDecoded = html_entity_decode($response);


//        $xml = simplexml_load_string($responseDecoded); // load a SimpleXML object

//        Log::toFile(print_r($xml));
//        $responseDecoded = str_replace("rdf:RDF", "", $responseDecoded);  // delete all rdf: values to manipulate input with SimpleXml object

        $responseDecoded = $this->stripResponse($response);

        Log::toFile($responseDecoded);

        $memModel = new MemModel();
        $memModel->loadFromString($responseDecoded, 'rdf');

//        Log::toFile($memModel->getBaseURI());
//        Log::toFile($memModel->getIndexType());
//        Log::toFile(print_r($memModel->getParsedNamespaces(), true));
//        Log::toFile($memModel->getUniqueResourceURI());

//        Log::toFile(print_r($memModel->triples, true));

//        Log::toFile(json_encode($memModel->triples));

//        $sentic_replace1 = str_replace("rdf:", "", $responseDecoded);  // delete all rdf: values to manipulate input with SimpleXml object
//        $sentic_replace2 = str_replace("c:", "", $sentic_replace1);  // delete all rdf: values to manipulate input with SimpleXml object
//        $xml = simplexml_load_string($sentic_replace2); // load a SimpleXML object

        $it = $memModel->getStatementIterator();
        $found = array();

        while ($it->hasNext()) {
            $statement = $it->next();
//            echo "Statement number: " . $it->getCurrentPosition() . "<BR>";
//            echo "Subject: " . $statement->getLabelSubject() . "<BR>";
//            echo "Predicate: " . $statement->getLabelPredicate() . "<BR>";
//            echo "Object: " . $statement->getLabelObject() . "<P>";

            $subj = $statement->getLabelSubject();
            $pred = substr($statement->getLabelPredicate(), (strrpos($statement->getLabelPredicate(), "/") + 1));

            if ( array_key_exists($subj, $found) ) {
                $found[$subj][$pred] = $statement->getLabelObject();
            } else {
                $labels = array();
                $labels[$pred] = $statement->getLabelObject();
                $found[$subj] = $labels;
            }

            if( $statement->getLabelPredicate() == "http://www.w3.org/1999/02/22-rdf-syntax-ns#type") {

                $type = substr($statement->getLabelObject(), (strrpos($statement->getLabelObject(), "/") + 1));

                $found[$subj]['type'] = $type;
            }

        }

//        $items = array();
//
//        foreach($memModel->triples as $value) {
////            Log::toFile(print_r($value, true));
//            $subj = $value->subj->uri;
//
//            if ( property_exists($value->obj, "label") ) {
//
//                if ( array_key_exists($subj, $items) ) {
//                    $items[$subj][$value->pred->uri] = $value->obj->label;
//                } else {
//                    $labels = array();
//                    $labels[$value->pred->uri] = $value->obj->label;
//                    $items[$subj] = $labels;
//                }
//            }
//        }

        $items = $found;

        $toReturn = array();
        foreach ($items as $key=>$value ) {

//            Log::toFile($key);

//            if (strpos($key, "http://d.opencalais.com/er/company/") !== false) {
//                $items[$key]['tag'] = "company";
//                $toReturn[$key] = $items[$key];
//
//            }
//
//            if (strpos($key, "http://d.opencalais.com/er/geo/city/") !== false) {
//                $items[$key]['tag'] = "city";
//                $toReturn[$key] = $items[$key];
//            }


            // Working on the assumption that dochash entries are junk
            if(strpos($key, "http://d.opencalais.com/dochash") === false) {
                $toReturn[] = $items[$key];
            }

        }

        $toReturn['length'] = sizeof($toReturn);

//        Log::toFile(print_r($toReturn, true));

//        $xml_to_json = json_decode(json_encode($xml), 1);

        return $toReturn;
    }

    /**
     * Strip the encompassing string tag and return the inner RDF
     * Stolen from OpenCalais demo, not sure why CDATA tags arent used instead...
     */
    function stripResponse($response) {

        global $level;
        global $ret_parse;

        $ret_parse = "";
        $level = 0;

        $xmlp = xml_parser_create();
        xml_parser_set_option($xmlp, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($xmlp, XML_OPTION_SKIP_WHITE, 0);
        xml_set_element_handler($xmlp, "start", "stop");
        xml_set_character_data_handler($xmlp, "char");
        xml_parse($xmlp, $response, 1)or die(sprintf("XML Error: %s at line %d",
            xml_error_string(xml_get_error_code($xmlp)),
            xml_get_current_line_number($xmlp)));

        xml_parser_free($xmlp);

        return $ret_parse;
    }

    public static function postToURL($url, $fields) {

        $fieldString = OpenCalais2::preparePostFields($fields);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    static function preparePostFields($array) {
        $params = array();

        foreach ($array as $key => $value) {
            $params[] = $key . '=' . urlencode($value);
        }

        return implode('&', $params);
    }

}

function start($parser, $element_name, $element_atts) {

    global $level;
    global $ret_parse;

    if ($level > 0)
    {
        $ret_parse .= "<".$element_name;
        foreach ($element_atts as $name => $value)
        {
            $ret_parse .= " ".$name."=\"".$value."\"";
        }
        $ret_parse .= ">";
    }
    $level++;

}

function stop($parser, $element_name) {

    global $level;
    global $ret_parse;

    $level--;
    if ($level > 0)
    {
        $ret_parse .= "</".$element_name.">";
    }

}

function char($parser, $data) {

    global $level;
    global $ret_parse;

    if ($level > 0)
    {
        $ret_parse .= $data;
    }
}