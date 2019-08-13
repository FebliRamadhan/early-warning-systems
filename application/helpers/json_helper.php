<?php
  if ( ! function_exists('resultJson'))
  {
      function resultJson($responseError = true, $responseCode = "", $responseDesc = "", $data){
          $dataCount = count($data);
          // return array("responseError" => $responseError, "responseCode" => $responseCode ,"responseDesc" => $responseDesc, "data" => $data);
          return array("error" => $responseError,"code" => $responseCode,"message" => $responseDesc, "dataCount"=> $dataCount,"data" => $data);
      }
  }
?>