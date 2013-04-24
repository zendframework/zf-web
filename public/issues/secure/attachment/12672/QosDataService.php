<?php
require_once ('QosPortalService.php');

class QosDataService extends QosPortalService {
	
	/**
	 * Get results for report - weekly
	 *
	 * @param VOReport
	 * @return VOKpiValues
	 */
	public function getWeekly($report, $gid = null) {
		
		return $this->getData ( $report, $gid, 'weekly' );
	}
	
	/**
	 * Get results for report - daily
	 *
	 * @param VOReport
	 * @return VOKpiValues
	 */
	public function getDaily($report, $gid = null) {
		
		return $this->getData ( $report, $gid, 'daily' );
	}
	
	private function getData($report, $gid, $aggregationType) {
		
		# get report dates [Zend_Date]
		$startDate = $report->dateFrom;
		$endDate = $report->dateTo;
		
		$this->writeLog ( 'info', __METHOD__, __LINE__, "report name=$report->name from=$startDate to=$endDate" );
		
		if ($endDate->compare ( $startDate ) == - 1) {
			
			$errText = "end date > start date";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::INVALID_PARAM, $errText );
		}
		
		$cache_id = 'result_' . $report->rid . '_' . $aggregationType . '_' . $gid . '_' . $report->dateFrom->toString ( 'yyyyMMdd' ) . '_' . $report->dateTo->toString ( 'yyyyMMdd' );
		$this->writeLog ( 'info', __METHOD__, __LINE__, "cache_id=$cache_id" );
		
		if (! ($this->_cache->test ( $cache_id ))) { # check if result is in cache
			

			# calculate start day of week
			$startDate->sub ( $startDate->get ( Zend_Date::WEEKDAY_8601 ) - 1, Zend_Date::DAY );
			
			# calculate end day of week
			$endDate->sub ( $endDate->get ( Zend_Date::WEEKDAY_8601 ), Zend_Date::DAY );
			$endDate->add ( 7, Zend_Date::DAY );
			
			# get type of aggregation (AVG,SUM,%) and precision
			$reportProperties = $this->_db->fetchRow ( 'SELECT data_operator, resultPrecision, min, max, handle_datamissing, handle_over_max, handle_below_min FROM qos_reports WHERE rid=?', $report->rid );
			if (empty ( $reportProperties )) {
				
				$errText = "report properties are empty";
				$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
				return new VOError ( VOError::MISSING_REPORT_INFO, $errText );
			}
			
			$dataOperator = $reportProperties->data_operator;
			if ($dataOperator == "%") { # data operator cannot be % - used AVG instead for query
				$dataOperator = 'avg';
			}
			$precision = ( int ) $reportProperties->resultPrecision;
			$handeDataMissing = $reportProperties->handle_datamissing;
			$handleBelowMin = $reportProperties->handle_below_min;
			$handleOverMax = $reportProperties->handle_over_max;
			$reportMin = $reportProperties->min;
			$reportMax = $reportProperties->max;
									
			$kidsArray = $this->_db->fetchCol ( 'SELECT kid FROM kpis_for_report WHERE rid=? ORDER BY `index` ASC', array ($report->rid ) );
			if (count ( $kidsArray ) < 1) { # check if selected reports has kpis
				$errText = "No kpis found for selected report";
				$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
				return new VOError ( VOError::INVALID_PARAM, $errText );
			}
			
			# get results from database
			foreach ( $kidsArray as $kid ) {
				
				if ($aggregationType == 'weekly') {
					
					$query = 'SELECT DATE_ADD(date, INTERVAL(0-WEEKDAY(date)) DAY) as date, ROUND(' . $dataOperator . '(value),' . $precision . ') as value, COUNT(*) as count FROM qos_kpi_values
								WHERE kid=? AND deleted=? AND date BETWEEN ? AND ?';
					$arguments = array ($kid, 0, $startDate->toString ( 'yyyy-MM-dd' ), $endDate->toString ( 'yyyy-MM-dd' ) );
					
					if (! empty ( $handleBelowMin ) && ($handleBelowMin == 'average') || ! empty ( $handeDataMissing ) && ($handeDataMissing == 'average')) { # if handleBelowMin is set to average -> remove all values that are below min
						if (! empty ( $reportMin )) { # check if min not empty
							$query .= ' AND value > ?';
							array_push ( $arguments, $reportMin );
						}
					}
					
					if (! empty ( $handleOverMax ) && ($handleOverMax == 'average') || ! empty ( $handeDataMissing ) && ($handeDataMissing == 'average')) { # if handleOverMax is set to average -> remove all values that are above max
						if (! empty ( $reportMax )) {
							$query .= ' AND value < ?';
							array_push ( $arguments, $reportMax );
						}
					}
					
					$query .= ' GROUP BY YEARWEEK(date, 1) ORDER BY date';
					$result [$kid] = $this->_db->fetchAll ( $query, $arguments );
				
				} elseif ($aggregationType == 'daily') {
					$query = 'SELECT date, ROUND(value,' . $precision . ') as value, COUNT(*) as count FROM qos_kpi_values WHERE kid=? AND deleted=? AND date BETWEEN ? AND ?';
					$arguments = array ($kid, 0, $startDate->toString ( 'yyyy-MM-dd' ), $endDate->toString ( 'yyyy-MM-dd' ) );
					
					if (! empty ( $handleBelowMin ) && ($handleBelowMin == 'average') || ! empty ( $handeDataMissing ) && ($handeDataMissing == 'average')) {
						$query .= ' AND value > ?';
						array_push ( $arguments, $reportMin );
					}
					
					if (! empty ( $handleOverMax ) && ($handleOverMax == 'average') || ! empty ( $handeDataMissing ) && ($handeDataMissing == 'average')) {
						$query .= ' AND value < ?';
						array_push ( $arguments, $reportMax );
					}
					
					$query .= ' GROUP BY date ORDER BY date';
					
					$result [$kid] = $this->_db->fetchAll ( $query, $arguments );
				}
			}
			
			# count number of elements in each kid
			$hasResults = false;
			foreach ( $result as $kid => $k ) {
				$count [$kid] = (count ( $k ) - 1);
				if ($count [$kid] != - 1) {
					$hasResults = true;
				}
				$i [$kid] = 0; # i -> for each kid
			}
			
			# all kids for selected range have no results
			if (! $hasResults) {
				$errText = "No results for selected period.";
				$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
				return new VOError ( VOError::NO_DATA, $errText );
			}
			
			$dateAdd = 7; # default
			if ($aggregationType == 'daily') # set to 1 for daily
				$dateAdd = 1;
			
			$startDateLoop = new Zend_Date ( );
			$startDateLoop->set ( $startDate );
			$kvArray = array ();
			while ( ($endDate->compare ( $startDateLoop )) != - 1 ) { # start <-> end date loop
				

				$currentDate = $startDateLoop->toString ( 'yyyy-MM-dd' );
				
				$kpiValues = new VOKpiValues ( ); # create VOKpiValues for each day
				$kpiValues->date = new Zend_Date ( $currentDate, 'yyyy-MM-dd' );
				$kpiValues->mydate = $currentDate;
				$kpiValues->comments = $this->getReportDateCommentsByDate ( $report->rid, $currentDate, $gid, $aggregationType ); # report comments
				

				foreach ( $kidsArray as $kid ) {
					
					$kpiValue = new VOKpiValue ( ); # create VOKpiValue for each kid
					

					if (isset ( $result [$kid] [$i [$kid]] )) { # exists
						if ($startDateLoop->equals ( $result [$kid] [$i [$kid]]->date, Zend_Date::DATES )) { # date match (current loop date == current data date)
							

							$kpiValue->value = $this->handleMinMax ( $result [$kid] [$i [$kid]]->value, $reportMin, $handleBelowMin, $reportMax, $handleOverMax );
							
							$kpiValue->error = $this->getErrorText_someDataMissing ( $aggregationType, $result [$kid] [$i [$kid]]->count, $currentDate );
							$i [$kid] ++; # move to next element in array
						} else {
							$kpiValue->error = $this->getErrorText_allDataMissing ( $aggregationType, $currentDate );
						}
					} else { # all data missing for this week/day
						

						if ($topParent->name != 'SIGOS') {
							
							$kpiValue->value = null; # for 'drop' = null , for 'average' = null -> flex handle
							$kpiValue->error = $this->getErrorText_allDataMissing ( $aggregationType, $currentDate );
						} else {
							$kpiValue->value = null;
						}
					}
					
					# limits -> START
					$limitArray = array ();
					$limitObject = $this->checkForLimitLines ( $report->rid, $startDateLoop );
					# print_r ( $limitObject );
					if ($limitObject != false) {
						foreach ( $limitObject as $lo ) {
							
							$label = $lo->label;
							$color = $lo->color;
							$limitValue = $lo->value;
							
							# for limitlines
							if (! empty ( $kpiValue->value )) {
								if ($lo->upDn && ($kpiValue->value > $limitValue)) { # true -> over
									$rez = $kpiValue->value - $limitValue;
									$limit = new VOLimit ( $label, $color, $rez );
									$limitArray [] = $limit;
								} else if (! $lo->upDn && ($kpiValue->value < $lo->value)) { # false -> under
									$rez = $kpiValue->value - $limitValue;
									$limit = new VOLimit ( $label, $color, $rez );
									$limitArray [] = $limit;
								}
							}
							
							$kpiValues->$label = new VOLimitValue ( $limitValue );
						}
					} # limits -> END
					
			
					if (! empty ( $treshold_bad_value ) && ! empty ( $treshold_good_value ) && ! empty ( $kpiValue->value )) {
						if ($this->checkTresholdValue ( $treshold_good_operator, $treshold_good_value, $kpiValue->value )) {
							$kpiValue->tresholdValue = 3;
						} else if ($this->checkTresholdValue ( $treshold_bad_operator, $treshold_bad_value, $kpiValue->value )) {
							$kpiValue->tresholdValue = 1;
						} else {
							$kpiValue->tresholdValue = 2;
						}
					}
					
					
					$kpiValue->limits = $limitArray;
					$kpiValue->comment = $this->getKpiCommentsByDate ( $kid, $gid, $currentDate, $aggregationType ); # kpi comment
					

					$kpiValues->$kid = $kpiValue;
				
				} # KID LOOP
				

				$kvArray [] = $kpiValues;
				$startDateLoop->add ( $dateAdd, Zend_Date::DAY );
			} # weekly / daily loop
			

			$this->_cache->save ( $kvArray, $cache_id, array ('report_' . $report->rid ) );
		} else {
			$kvArray = $this->_cache->load ( $cache_id );
		}
		
		return $kvArray;
	}
	
	/*
	private function handleValueMissing($handleDataMissing) {
		
		$handleDataMissing = strtolower ( $handleDataMissing );
		$valid_array = array ('drop', 'average' );
		if (! in_array ( $handleDataMissing, $valid_array )) {
			$this->writeLog ( 'crit', __METHOD__, __LINE__, "unknown data missing handler: $handleDataMissing" );
			return null;
		}
		
		if ($handleDataMissing == 'average') {
			return (($previousValue + $nextValue) / 2);
		}
		
		return null;
	}
	*/
	
	private function handleMinMax($result, $reportMin, $handleBelowMin, $reportMax, $handleOverMax) {
		
		$this->writeLog ( 'info', __METHOD__, __LINE__, "result=$result reportMin=$reportMin handleBelowMin=$handleBelowMin, reportMax=$reportMax, handleOverMax=$handleOverMax" );
		
		$handleBelowMin = strtolower ( $handleBelowMin );
		$handleOverMax = strtolower ( $handleOverMax );
		
		$valid_array = array ('drop', 'show', 'replace', 'average' );
		if (! in_array ( $handleBelowMin, $valid_array )) {
			$this->writeLog ( 'crit', __METHOD__, __LINE__, "unknown below min handler: $handleBelowMin" );
			return null;
		}
		
		if (! in_array ( $handleOverMax, $valid_array )) {
			$this->writeLog ( 'crit', __METHOD__, __LINE__, "unknown over max handler: $handleOverMax" );
			return null;
		}
		
		if (! empty ( $reportMin )) { # not null
			if ($result < $reportMin) { # result is below Min
				

				$this->writeLog ( 'info', __METHOD__, __LINE__, "$result is under min ($reportMin) <br>" );
				
				if ($handleBelowMin == 'drop') {
					return null;
				} else if ($handleBelowMin == 'show') {
					return $result;
				} else if ($handleBelowMin == 'replace') {
					return $reportMin;
				} else if ($handleBelowMin == 'average') { # Flex handles average
					return null;
				}
			}
		}
		
		if (! empty ( $reportMax )) {
			
			if ($result > $reportMax) {
				
				$this->writeLog ( 'info', __METHOD__, __LINE__, "$result is over ($reportMax) <br>" );
				
				if ($handleOverMax == 'drop') {
					return null;
				} else if ($handleOverMax == 'show') {
					return $result;
				} else if ($handleOverMax == 'replace') {
					return $reportMax;
				} else if ($handleOverMax == 'average') { # Flex handles average
					return null;
				}
			}
		}
		
		return $result;
	}
	
	private function getErrorText_someDataMissing($aggregationType, $count, $date) { # not all data missing
		

		$err = null;
		
		if ($aggregationType == 'weekly') {
			if ($count < 7) { # data missing for this range
				

				$err = new VOError ( VOError::DATA_MISSING, "Data missing in this week" );
			}
		} elseif ($aggregationType == 'daily') {
			if ($count != 1) { # data missing for this range
				

				$err = new VOError ( VOError::DATA_MISSING, "Data count for $date=$count" );
			}
		}
		
		return $err;
	}
	
	private function getErrorText_allDataMissing($aggregationType, $date) {
		
		if ($aggregationType == 'daily')
			$errText = "Data missing for $date";
		elseif ($aggregationType == 'weekly')
			$errText = "Data missing for all days in week";
		
		return new VOError ( VOError::DATA_MISSING, $errText );
	}
	
	public function getDailyMinMax($report) {
		return $this->getReportMinMax ( $report, 'daily' );
	}
	
	public function getWeeklyMinMax($report) {
		return $this->getReportMinMax ( $report, 'weekly' );
	}
	
	private function getReportMinMax($report, $aggregationType) {
		
		# check for empty dates
		if (empty ( $report->dateFrom ) || empty ( $report->dateTo )) {
			
			$errText = "empty dateFrom or DateTo fields";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::INVALID_PARAM, $errText );
		}
		
		$startDate = $report->dateFrom;
		$endDate = $report->dateTo;
		
		$this->writeLog ( 'info', __METHOD__, __LINE__, "dateFROM=" . $startDate->toString ( 'yyyy-MM-dd' ) . " dateTO=" . $endDate->toString ( 'yyyy-MM-dd' ) );
		
		if ($endDate->compare ( $startDate ) == - 1) {
			
			$errText = "end date > start date";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::INVALID_PARAM, $errText );
		}
		
		$dataOperator = $report->dataOperator;
		if ($dataOperator == "%") # data operator cannot be % - used avg instead for query
			$dataOperator = 'avg';
		
		$kids = $this->_db->fetchAll ( 'SELECT kid FROM kpis_for_report WHERE rid=?', array ($report->rid ) );
		if (empty ( $kids )) {
			$errText = "No KIDs found for this report";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::NO_KPIS, $errText );
		}
		
		$kidForMinMax = '';
		foreach ( $kids as $k ) {
			
			$kidForMinMax .= "'" . $k->kid . "',";
		}
		
		$kidForMinMax = substr ( $kidForMinMax, 0, - 1 );
		
		if ($aggregationType == 'daily') {
			$minMax = $this->_db->fetchRow ( "SELECT MIN(value) as min, MAX(value) as max FROM qos_kpi_values WHERE kid IN ($kidForMinMax) AND date BETWEEN ? AND ? AND deleted=?", array ($startDate->toString ( 'yyyy-MM-dd' ), $endDate->toString ( 'yyyy-MM-dd' ), 0 ) );
			if (empty ( $minMax ) || empty ( $minMax->min ) || empty ( $minMax->max )) {
				$errText = "min/max is empty";
				$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
				return new VOError ( VOError::INVALID_PARAM, $errText );
			}
			return $minMax;
		} else if ($aggregationType == 'weekly') {
			
			$endDate->sub ( $endDate->get ( Zend_Date::WEEKDAY_8601 ), Zend_Date::DAY );
			$endDate->add ( 7, Zend_Date::DAY );
			
			$result = $this->_db->fetchAll ( "SELECT " . $dataOperator . "(value) as r FROM qos_kpi_values WHERE kid IN ($kidForMinMax) AND date BETWEEN ? AND ? AND deleted=? GROUP BY YEARWEEK(date, 1), kid ORDER BY r", array ($startDate->toString ( 'yyyy-MM-dd' ), $endDate->toString ( 'yyyy-MM-dd' ), 0 ) );
			if (count ( $result ) < 1) {
				$errText = "min/max is empty";
				$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
				return new VOError ( VOError::INVALID_PARAM, $errText );
			}
			
			$r = new stdClass ( );
			$r->min = $result [0]->r; # first element is min
			$r->max = $result [count ( $result ) - 1]->r; # last element is max
			return $r;
		}
		
		$errText = "unknown aggregation type";
		$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
		return new VOError ( VOError::INVALID_PARAM, $errText );
	}
	
	private function checkTresholdValue($tresholdOperator, $tresholdValue, $value) {
		
		if ($tresholdOperator == '>') {
			if ($value >= $tresholdValue) {
				return true;
			}
		} elseif ($tresholdOperator == '<') {
			if ($value <= $tresholdValue)
				return true;
		}
		
		return false;
	}
	
	public function getCategoryData($report, $gid) {
		
		# check for empty date
		if (empty ( $report->dateFrom ) || empty ( $report->dateTo )) {
			
			$errText = "empty dateFrom or DateTo fileds";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::INVALID_PARAM, $errText );
		}
		
		if (! $report->dateFrom instanceof Zend_Date) {
			$errText = "report dateFrom is not a Zend_Date object";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::INVALID_PARAM, $errText );
		}
		
		if (! $report->dateTo instanceof Zend_Date) {
			$errText = "report dateTo is not a Zend_Date object";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::INVALID_PARAM, $errText );
		}
		
		if (empty ( $gid )) {
			$errText = "group id is empty";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::INVALID_PARAM, $errText );
		}
		
		$topParent = $this->getTopParent ( new VOLogicalReportGroup ( $report->parentId ) );
		
		$startDate = $report->dateFrom;
		$endDate = $report->dateTo;
		
		# TODO: remove in production
		$startDate = new Zend_Date ( '2009-06-29', 'yyyy-MM-dd' );
		$endDate = new Zend_Date ( '2009-06-29', 'yyyy-MM-dd' );
		
		$this->writeLog ( 'info', __METHOD__, __LINE__, "dateFrom=$startDate dateTo=$endDate" );
		
		$selectedCategories = $this->_db->fetchAll ( 'SELECT cat.* FROM category_for_lrg cl, qos_category cat WHERE cat.cid=cl.cid AND cl.lrgid=?', array ($report->parentId ) );
		if (count ( $selectedCategories ) == 0) {
			
			$errText = "No categories seleceted for this reports";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::NO_CATEGORY, $errText );
		}
		
		$c = new stdClass ( );
		foreach ( $selectedCategories as $cat ) {
			$c->name = $cat->name;
			$c->label = $cat->label;
			$categories [$cat->cid] = $c;
		}
		
		# get report properties
		$reportProperties = $this->_db->fetchRow ( 'SELECT data_operator, resultPrecision FROM qos_reports WHERE rid=?', $report->rid );
		if (empty ( $reportProperties )) {
			$errText = "report properties are empty";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::MISSING_REPORT_INFO, $errText );
		}
		
		$dataOperator = $reportProperties->data_operator;
		if ($dataOperator == "%") # data operator cannot be % - used avg instead for query
			$dataOperator = 'avg';
		$precision = ( int ) $reportProperties->resultPrecision;
		
		$kids = $this->_db->fetchCol ( 'SELECT kid FROM kpis_for_report WHERE rid=? ORDER BY `index` ASC', array ($report->rid ) );
		if (count ( $kids ) < 1) { # check if selected reports has kpis
			$errText = "No kpis found for selected report";
			$this->writeLog ( 'err', __METHOD__, __LINE__, $errText );
			return new VOError ( VOError::INVALID_PARAM, $errText );
		}
		
		# generate a string containing all cids -> used in query
		$cids = implode ( "','", array_keys ( $categories ) );
		$cids = "'" . $cids . "'";
		
		# echo "cids=$cids <br>";
		

		# get data foreach kid
		foreach ( $kids as $kid ) {
			
			if ((strtoupper ( $topParent->name )) == 'SIGOS') {
				$result [$kid] = $this->_db->fetchAll ( "SELECT cid, ROUND(" . $dataOperator . "(value)," . $precision . ") as value FROM qos_kpi_values WHERE date BETWEEN ? AND ? AND kid=? AND cid IN ($cids) AND deleted=? GROUP by cid ORDER BY date", array ($startDate->toString ( 'yyyy-MM-dd' ), $endDate->toString ( 'yyyy-MM-dd' ), $kid, 0 ) );
			} else { # REMEDY [ no SUM ]
				if (($endDate->compare ( $startDate )) == 0) { # start & end date are equal
					$result [$kid] = $this->_db->fetchAll ( "SELECT cid, ROUND(value," . $precision . ") as value FROM qos_kpi_values WHERE date=? AND kid=? AND cid IN ($cids) AND deleted=?", array ($startDate->toString ( 'yyyy-MM-dd' ), $kid, 0 ) );
				} else { # different start & end date
					

					foreach ( $selectedCategories as $categoryProperties ) { # calculate value
						

						# echo "kid=$kid cp=$categoryProperties->cid <br>";
						$resultCid = $this->_db->fetchRow ( "SELECT ((SELECT value FROM qos_kpi_values WHERE date=? AND kid=? AND cid=?) - (SELECT value FROM qos_kpi_values WHERE date=? AND kid=? AND cid=?)) as value", array ($endDate->toString ( 'yyyy-MM-dd' ), $kid, $categoryProperties->cid, $startDate->toString ( 'yyyy-MM-dd' ), $kid, $categoryProperties->cid ) );
						$stdObj = new stdClass ( );
						$stdObj->cid = $categoryProperties->cid;
						$stdObj->value = $resultCid->value;
						$result [$kid] [] = $stdObj;
					}
				}
				
				# get kpi tresholds for remedy
				$treshold [$kid] = $this->_db->fetchRow ( 'SELECT treshold_bad, treshold_good FROM qos_kpis WHERE kid=?', $kid );
				# echo "KID=$kid <br>";
				# print_r ( $treshold [$kid] );
				

				# echo "tb=" . $treshold [$kid]->treshold_bad . "<br>";
				

				if (empty ( $treshold [$kid]->treshold_bad )) {
					$treshold_bad_value [$kid] = null;
				} else {
					$treshold_parts = $treshold [$kid]->treshold_bad;
					$treshold_parts = explode ( " ", $treshold_parts );
					$treshold_bad_operator [$kid] = trim ( $treshold_parts [0] );
					$treshold_bad_value [$kid] = ( int ) trim ( $treshold_parts [1] );
					
				# print_r($treshold_bad_operator);
				# echo "tbo=" . $treshold_bad_operator [$kid] . " <br>";
				}
				
				if (empty ( $treshold [$kid]->treshold_good )) {
					$treshold_good_value [$kid] = null;
				} else {
					$treshold_parts = $treshold [$kid]->treshold_good;
					$treshold_parts = explode ( " ", $treshold_parts );
					$treshold_good_operator [$kid] = trim ( $treshold_parts [0] );
					$treshold_good_value [$kid] = ( int ) trim ( $treshold_parts [1] );
				}
				
			# echo "tbo=" . $treshold_bad_operator [$kid] . " tbv=" . $treshold_bad_value [$kid] . " <br>";
			# echo "tgo=" . $treshold_good_operator [$kid] . "tgv=" . $treshold_good_value [$kid] . " <br>";
			

			# exit ();
			

			} # is REMEDY
		} # kid loop
		

		# print_r ( $result );
		# exit ();
		

		$kvArray = array ();
		foreach ( $selectedCategories as $category ) { # vse kategorije, ki so dodane v AdminToolu
			

			$kv = new VOKpiValues ( );
			$kv->date = $startDate;
			$kv->categoryLabel = $category->name;
			
			# echo "trenuten cid=$category->cid name=> $category->name <br>";
			

			foreach ( $result as $kid => $catValues ) { # rezultati po kidih
				

				# echo "kid=$kid <br>"; # result=".print_r($catValues) ."<br>";
				$k = null;
				foreach ( $catValues as $cv ) { # -> loop cez vse kategorije za katere obstajajo rezultati
					# echo "cv cid=$cv->cid value=$cv->value <br>";
					

					if ($category->cid == $cv->cid) {
						$k = new VOKpiValue ( );
						$k->value = $cv->value;
						break;
					}
				}
				
				# this CID not found in result (but added in AdminTool) -> add null
				if (empty ( $k )) {
					$k = new VOKpiValue ( );
					$k->value = null;
				}
				
				# $k->tresholdValue = ( int ) 13369395;
				

				/*
				if ((strtoupper ( $topParent->name )) == 'REMEDY') {
					if (! empty ( $treshold_bad_value [$kid] ) && ! empty ( $k->value ))
						$k->tresholdValue = $this->handleRemedyTreshold ( $treshold_bad_operator [$kid], $treshold_bad_value [$kid], 'bad', $k->value );
					
					if (! empty ( $treshold_good_value [$kid] ) && ! empty ( $k->value ))
						$k->tresholdValue = $this->handleRemedyTreshold ( $treshold_good_operator [$kid], $treshold_good_value [$kid], 'good', $k->value );
				}
				
				if (! empty ( $treshold_bad_value [$kid] ) && ! empty ( $treshold_good_value [$kid] ) && ! empty ( $k->value )) {
					if ($this->checkTresholdValue ( $treshold_good_operator [$kid], $treshold_good_value, $k->value )) {
						$k->tresholdValue = 3;
					} else if ($this->checkTresholdValue ( $treshold_bad_operator [$kid], $treshold_bad_value, $k->value )) {
						$k->tresholdValue = 1;
					} else {
						$k->tresholdValue = 2;
					}
				}
				*/
				
				$kv->$kid = $k;
			}
			
			$kvArray [] = $kv;
			# echo "category=" . print_r ( $category ) . " <br>";
		}
		
		# print_r($kvArray);
		# exit ();
		

		return $kvArray;
	}
	
	private function handleRemedyTreshold($treshold_operator, $treshold_value, $treshold_type, $value) {
		
		if ($treshold_operator == '>') {
			
			$div = $treshold_value / 3;
			if ($value < $div) {
				return 'white';
			} else if (($value >= $div) && ($value < ($div * 2))) {
				return ($treshold_type == 'bad') ? 'l_red' : 'l_green';
			} else if (($value >= ($div * 2)) && ($value < ($div * 3))) {
				return ($treshold_type == 'bad') ? 'f_red' : 'f_green';
			} else if ($value > $treshold_value) {
				return ($treshold_type == 'bad') ? '#B20000' : 'green';
			}
		
		} else if ($treshold_operator == '<') {
			
			if ($value < $treshold_value) {
				return ($treshold_type == 'bad') ? 'red' : 'green';
			} else if (($value >= $treshold_value) && (($value < ($treshold_value * 2)))) {
				return ($treshold_type == 'bad') ? 'f_red' : 'f_green';
			} else if (($value >= ($treshold_value * 2)) && (($value < ($treshold_value * 3)))) {
				return ($treshold_type == 'bad') ? 'l_red' : 'l_green';
			} else {
				return 'white';
			}
		}
	
	}

} # end class
