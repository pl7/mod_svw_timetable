<?php

class ModSvwTimetableHelper {
    public function getTimeTableFromDb($start_date, $end_date, $start_time, $end_time) {
        
        $db = &JFactory::getDBO();

        $query = 'SELECT * FROM `svw_team_events` WHERE type = 0 AND (date_end < "'.$end_date.'" AND date_end > "'.$start_date.'") AND (time_begin >= "'.$start_time.'" AND time_begin < "'.$end_time.'") AND time_end > "'.$end_time.'" ORDER BY date_start ASC, date_end ASC';
        
        $db->setQuery($query);
        
        $items = ($items = $db->loadObjectList())?$items:array(); // loads results in an array
        
        return $items;
    }
    public function getTimeTableWeekDayFromDb($start_date, $end_date, $start_time, $end_time, $wd) {
        
        $db = &JFactory::getDBO();

        $query = 'SELECT * FROM `svw_team_events` WHERE 
					type = 0 
							AND 
					(date_end <= "'.$end_date.'" AND date_end >= "'.$start_date.'") 
							AND
					(
						(time_begin = "'.$start_time.'" AND time_end > "'.$end_time.'") 
								OR 
						(time_begin < "'.$start_time.'" AND time_end > "'.$end_time.'")
								OR
						(time_begin < "'.$start_time.'" AND time_end = "'.$end_time.'")
					)
							AND
					DAYOFWEEK(date_start) = "'.$wd.'" 
							AND 
					text LIKE "Training" 
					ORDER BY date_start ASC, date_end ASC';
		//echo $query."</br>";
        $db->setQuery($query);
        
        $items = ($items = $db->loadObjectList())?$items:array(); // loads results in an array
        
        return $items;
    }
    public function getTimeTableWeekDayAndPlaceFromDb($start_date, $end_date, $start_time, $end_time, $wd, $place) {
        
        $db = &JFactory::getDBO();

        $query = 'SELECT * FROM `svw_team_events` event 
					INNER JOIN svw_teams team ON team.team_key LIKE event.team_key
					WHERE 
					type = 0 
							AND 
					(event.date_end <= "'.$end_date.'" AND event.date_end >= "'.$start_date.'") 
							AND
					(
						(event.time_begin = "'.$start_time.'" AND event.time_end > "'.$end_time.'") 
								OR 
						(event.time_begin < "'.$start_time.'" AND event.time_end > "'.$end_time.'")
								OR
						(event.time_begin < "'.$start_time.'" AND event.time_end = "'.$end_time.'")
					)
							AND
					DAYOFWEEK(event.date_start) = "'.$wd.'" 
							AND 
					event.text LIKE "Training" 
							AND
					event.place_key = "'.$place.'" 
					ORDER BY date_start ASC, date_end ASC';
		//echo $query."</br>";
        $db->setQuery($query);
        
        $items = ($items = $db->loadObjectList())?$items:array(); // loads results in an array
        
        return $items;
    }
    public function getTimeTableWeekDayByStringFromDb($start_date, $end_date, $start_time, $end_time, $wd, $s) {
        
        $db = &JFactory::getDBO();
        $query = 'SELECT * FROM `svw_team_events` event
				INNER JOIN svw_teams team ON team.team_key LIKE event.team_key
				WHERE 
					event.type = 0 
							AND 
					(event.date_end < "'.$end_date.'" AND event.date_end > "'.$start_date.'") 
							AND 
					(
						(event.time_begin = "'.$start_time.'" AND event.time_end > "'.$end_time.'") 
								OR 
						(event.time_begin < "'.$start_time.'" AND event.time_end > "'.$end_time.'")
								OR
						(event.time_begin < "'.$start_time.'" AND event.time_end = "'.$end_time.'")
					)
							AND
					DAYOFWEEK(event.date_start) = "'.$wd.'" 
							AND 
					event.text LIKE "'.$s.'" 
					ORDER BY event.date_start ASC, event.date_end ASC';
        /*$query = 'SELECT * FROM `svw_team_events` WHERE type = 0 AND (date_end < "'.$end_date.'" AND date_end > "'.$start_date.'") AND (time_begin >= "'.$start_time.'" AND  time_begin < "'.$end_time.'") AND time_end >= "'.$end_time.'" AND DAYOFWEEK(date_start) = "'.$wd.'" AND text LIKE "'.$s.'"ORDER BY date_start ASC, date_end ASC';*/

        $db->setQuery($query);
        
        $items = ($items = $db->loadObjectList())?$items:array(); // loads results in an array
        
        return $items;
    }
	public function displayTimeTableRow($from, $to, $startTime, $endTime) {
		$begin = new DateTime('2000-01-01 '.$startTime);
		$end = new DateTime('2000-01-01 '.$endTime);
		?><tr>
			<td class="time"><?php echo $begin->format('H:i')."-".$end->format('H:i')?></td>
			
			<!-- Montag -->
			<?php $day = 2; ?>
			<?php $weekDayList = ModSvwTimetableHelper::getTimeTableWeekDayFromDb($from, $to, $startTime, $endTime, $day); ?>
			<?php $weekDayListA = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 1); ?>
			<?php $weekDayListB = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 2); ?>
			<?php $weekDayListC = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 3); ?>
			<?php if(!sizeof($weekDayListC) > 0) { ?>
			<td class="fieldA <?php 
			 if(sizeof($weekDayListA) > 0) {
    			 if(strlen($weekDayListA[0]->key)==0) {
    			     echo $weekDayListA[0]->team_key.' small">'.$weekDayListA[0]->short_key;
    			 }
    			 else {
    			     echo $weekDayListA[0]->team_key.'">'.$weekDayListA[0]->key;
    			 }
			 }
             else echo '">';?><?php if(sizeof($weekDayListA) > 1) echo  "/".$weekDayListA[1]->key;?></td>
			<td class="fieldB <?php 
			 if(sizeof($weekDayListB) > 0) 
			     if(strlen($weekDayListB[0]->key)==0){
			         echo $weekDayListB[0]->team_key.' small">'.$weekDayListB[0]->short_key;
			     } else{
			         echo $weekDayListB[0]->team_key.'">'.$weekDayListB[0]->key;
			     }
			 else echo '">';?><?php if(sizeof($weekDayListB) > 1) echo  "/".$weekDayListB[1]->key;?></td>			
			<?php } else { ?>
			<td colspan="2" class="fieldC <?php if(sizeof($weekDayListC) > 0) echo $weekDayListC[0]->team_key.'">'.$weekDayListC[0]->key;else echo '">';?><?php if(sizeof($weekDayListC) > 1) echo  "/".$weekDayListC[1]->key;?></td>
			<?php }?>
			<!-- Dienstag -->
			<?php $day = 3; ?>
			<?php $weekDayList = ModSvwTimetableHelper::getTimeTableWeekDayFromDb($from, $to, $startTime, $endTime, $day); ?>
			<?php $weekDayListA = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 1); ?>
			<?php $weekDayListB = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 2); ?>
			<?php $weekDayListC = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 3); ?>
			<?php if(!sizeof($weekDayListC) > 0) { ?>
			<td class="fieldA <?php if(sizeof($weekDayListA) > 0) echo $weekDayListA[0]->team_key.'">'.$weekDayListA[0]->key;else echo '">';?><?php if(sizeof($weekDayListA) > 1) echo  "/".$weekDayListA[1]->key;?></td>
			<td class="fieldB <?php if(sizeof($weekDayListB) > 0) echo $weekDayListB[0]->team_key.'">'.$weekDayListB[0]->key;else echo '">';?><?php if(sizeof($weekDayListB) > 1) echo  "/".$weekDayListB[1]->key;?></td>			
			<?php } else { ?>
			<td colspan="2" class="fieldC <?php if(sizeof($weekDayListC) > 0) echo $weekDayListC[0]->team_key.'">'.$weekDayListC[0]->key;else echo '">';?><?php if(sizeof($weekDayListC) > 1) echo  "/".$weekDayListC[1]->key;?></td>
			<?php }?>
			<?php $weekDayList = ModSvwTimetableHelper::getTimeTableWeekDayByStringFromDb($from, $to, $startTime, $endTime, 3,"Hallentraining"); ?>                
			<td class="hall <?php if(sizeof($weekDayList) > 0) echo $weekDayList[0]->team_key.'">'.$weekDayList[0]->key;else echo '">';?><?php if(sizeof($weekDayList) > 1) echo  "/".$weekDayList[1]->key;?></td>			
			<!-- Mittwoch -->
			<?php $day = 4; ?>
			<?php $weekDayList = ModSvwTimetableHelper::getTimeTableWeekDayFromDb($from, $to, $startTime, $endTime, $day); ?>
			<?php $weekDayListA = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 1); ?>
			<?php $weekDayListB = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 2); ?>
			<?php $weekDayListC = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 3); ?>
			<?php if(!sizeof($weekDayListC) > 0) { ?>
			<td class="fieldA <?php if(sizeof($weekDayListA) > 0) echo $weekDayListA[0]->team_key.'">'.$weekDayListA[0]->key;else echo '">';?><?php if(sizeof($weekDayListA) > 1) echo  "/".$weekDayListA[1]->key;?></td>
			<td class="fieldB <?php if(sizeof($weekDayListB) > 0) echo $weekDayListB[0]->team_key.'">'.$weekDayListB[0]->key;else echo '">';?><?php if(sizeof($weekDayListB) > 1) echo  "/".$weekDayListB[1]->key;?></td>			
			<?php } else { ?>
			<td colspan="2" class="fieldC <?php if(sizeof($weekDayListC) > 0) echo $weekDayListC[0]->team_key.'">'.$weekDayListC[0]->key;else echo '">';?><?php if(sizeof($weekDayListC) > 1) echo  "/".$weekDayListC[1]->key;?></td>
			<?php }?>
			<!-- Donnerstag -->
			<?php $day = 5; ?>
			<?php $weekDayList = ModSvwTimetableHelper::getTimeTableWeekDayFromDb($from, $to, $startTime, $endTime, $day); ?>
			<?php $weekDayListA = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 1); ?>
			<?php $weekDayListB = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 2); ?>
			<?php $weekDayListC = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 3); ?>
			<?php if(!sizeof($weekDayListC) > 0) { ?>
			<td class="fieldA <?php if(sizeof($weekDayListA) > 0) echo $weekDayListA[0]->team_key.'">'.$weekDayListA[0]->key;else echo '">';?><?php if(sizeof($weekDayListA) > 1) echo  "/".$weekDayListA[1]->key;?></td>
			<td class="fieldB <?php if(sizeof($weekDayListB) > 0) echo $weekDayListB[0]->team_key.'">'.$weekDayListB[0]->key;else echo '">';?><?php if(sizeof($weekDayListB) > 1) echo  "/".$weekDayListB[1]->key;?></td>			
			<?php } else { ?>
			<td colspan="2" class="fieldC <?php if(sizeof($weekDayListC) > 0) echo $weekDayListC[0]->team_key.'">'.$weekDayListC[0]->key;else echo '">';?><?php if(sizeof($weekDayListC) > 1) echo  "/".$weekDayListC[1]->key;?></td>
			<?php }?>
			<?php $weekDayList = ModSvwTimetableHelper::getTimeTableWeekDayByStringFromDb($from, $to, $startTime, $endTime, 5,"Hallentraining"); ?>                
			<td class="hall <?php if(sizeof($weekDayList) > 0) echo $weekDayList[0]->team_key.'">'.$weekDayList[0]->key;else echo '">';?><?php if(sizeof($weekDayList) > 1) echo  "/".$weekDayList[1]->key;?></td>			
			<!-- Freitag -->
			<?php $day = 6; ?>
			<?php $weekDayList = ModSvwTimetableHelper::getTimeTableWeekDayFromDb($from, $to, $startTime, $endTime, $day); ?>
			<?php $weekDayListA = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 1); ?>
			<?php $weekDayListB = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 2); ?>
			<?php $weekDayListC = ModSvwTimetableHelper::getTimeTableWeekDayAndPlaceFromDb($from, $to, $startTime, $endTime, $day, 3); ?>
			<?php if(!sizeof($weekDayListC) > 0) { ?>
			<td class="fieldA <?php if(sizeof($weekDayListA) > 0) echo $weekDayListA[0]->team_key.'">'.$weekDayListA[0]->key;else echo '">';?><?php if(sizeof($weekDayListA) > 1) echo  "/".$weekDayListA[1]->key;?></td>
			<td class="fieldB <?php if(sizeof($weekDayListB) > 0) echo $weekDayListB[0]->team_key.'">'.$weekDayListB[0]->key;else echo '">';?><?php if(sizeof($weekDayListB) > 1) echo  "/".$weekDayListB[1]->key;?></td>			
			<?php } else { ?>
			<td colspan="2" class="fieldC <?php if(sizeof($weekDayListC) > 0) echo $weekDayListC[0]->team_key.'">'.$weekDayListC[0]->key;else echo '">';?><?php if(sizeof($weekDayListC) > 1) echo  "/".$weekDayListC[1]->key;?></td>
			<?php }?>
			<?php $weekDayList = ModSvwTimetableHelper::getTimeTableWeekDayFromDb($from, $to, $startTime, $endTime, 7); ?>
			<td class="hall <?php if(sizeof($weekDayList) > 0) echo $weekDayList[0]->team_key.'">'.$weekDayList[0]->key;else echo '">';?><?php if(sizeof($weekDayList) > 1) echo  "/".$weekDayList[1]->key;?></td>			
		</tr><?
	}
  }
    
?>