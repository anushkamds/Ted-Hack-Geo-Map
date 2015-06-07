<?php

require_once 'DbManager.php';

class DriverSearch {
	function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
		$earth_radius = 6371;  
		  
		$dLat = deg2rad($latitude2 - $latitude1);  
		$dLon = deg2rad($longitude2 - $longitude1);  
		  
		$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
		$c = 2 * asin(sqrt($a));  
		$d = $earth_radius * $c;  
		  
		return $d;  
	}

	function getNearByLocations($originLocation, $radius) {
		list($latitudeX, $longitudeX) = $originLocation;
		$latRange = array($latitudeX - 1, $latitudeX + 1); // getLatRange($latitudeX, $radius);
		$longRange = array($longitudeX - 1, $longitudeX + 1); // getLongRange($longitudeX, $radius);
		$query = 'select id, haversine(`lat`, `log`, ?, ?) as distance from `location` where (`lat` BETWEEN ? AND ?) AND (`log` BETWEEN ? AND ?) having distance > ?';
		$sth = DbManager::getConnection()->prepare($query);
		$params = array($latitudeX, $longitudeX, $latRange[0], $latRange[1], $longRange[0], $longRange[1], $radius);
		$sth->execute($params);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		if (function_exists('array_column')) {
			return array_column($result, 'distance', 'id');
		} else {
			$ret = array();
			foreach ($result as $row) {
				$ret[$row['id']] = $row['distance'];
			}
			return $ret;
		}
	}

	function getRouteIdsByLocations($locations) {
		$placeHolder = array_fill(0, count($locations), '?');
		$query = 'select `route_id` from `way_point` where `location_id` IN (' . implode(',', $placeHolder) . ')';
		$sth = DbManager::getConnection()->prepare($query);
		$sth->execute($locations);
		return $sth->fetchAll(PDO::FETCH_COLUMN);
	}

	function getVicinityRadius($source, $destination) {
		list($latitudeSource, $longitudeSource) = $source;
		list($latitudeDestination, $longitudeDestination) = $destination;
		$distance = $this->getDistance($latitudeSource, $longitudeSource, $latitudeDestination, $longitudeDestination);
		return $distance < 50 ? $distance / 10 : 5;
	}

	function getMatchingRoutes($source, $destination) {	
		$radius = 5; // getVicinityRadius($source, $destination);
		$sourceNearbyLocations = $this->getNearByLocations($source, $radius);
		if ($sourceNearbyLocations) {
			$destinationNearBYLocations = $this->getNearByLocations($destination, $radius);
			if ($destinationNearBYLocations) {
				$routesThoughSource = $this->getRouteIdsByLocations(array_keys($sourceNearbyLocations));
				$routesThoughDestination = $this->getRouteIdsByLocations(array_keys($destinationNearBYLocations));
				$matchingRoutes = array_intersect($routesThoughSource, $routesThoughDestination);
				// sortRoutes($matchingRoutes, $sourceNearbyLocations, $destinationNearBYLocations);
				return $matchingRoutes;
			}
		}
		return array();
	}

	public function getMatchingDrivers($source, $destination, $limit = 5) {
		$routeIds = $this->getMatchingRoutes($source, $destination);
		if (empty($routeIds)) {
			return array();
		}
		$limit = (int) $limit;
		$driverIds = DbManager::getConnection()->query('select driver_id from `route` where `id` in (' . implode(',', $routeIds) . ") LIMIT $limit")->fetchAll(PDO::FETCH_COLUMN);
		$query = 'select * from `driver` where `id` in (' . implode(',', $routeIds) . ")";
		return DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
	}
}
