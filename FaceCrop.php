<?php

class Face {

	public $x = 0;
	public $y = 0;
	public $width = 0;
	public $height = 0;

	public function __construct($responseObj) {
		$this->x = $responseObj["x"];
		$this->y = $responseObj["y"];
		$this->width = $responseObj["width"];
		$this->height = $responseObj["height"];
	}

}

class FaceCrop { 

	private $apiKey = "";

	public function __construct($key) {
		$this->apiKey = $key;
	}

	public function cropFaces($imageData) {
		$faces = getFaceBoundaries($imageData);
		$faceImages = array();

		foreach($faces as $face) {
			$image = imagecrop($imageData, $face);

			array_push($faceImage, $image);
		}

		return $faceImages;
	}

	public function getFaceBoundaries($imageData) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->getRequestUrl());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $imageData);  

		$faces = array();
		$rawResponse = curl_exec($ch);
		$response = json_decode($rawResponse, true);

		if($response["result"] === "success") {
			foreach($response["people"] as $person) {
				$face = new Face($person["location"]);

				array_push($faces, $face);
			}

			return $faces;
		}
		else {
			return null;
		}
	}

	private function getRequestUrl() {
		$format = "http://api.haystack.ai/api/image/analyze?output=json&apikey=%s&model=gender";

		return sprintf($format, $this->apiKey);
	}

}
