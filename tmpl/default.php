<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
    
    jimport( 'joomla.filesystem.file' );
    $canEdit = JFactory::getUser()->authorise('core.edit', 'com_languages');
    
    //get the items to display from the helper
    $eventList = ModSvwTimetableHelper::getTimeTableWeekDayFromDb($from, $to, "15:59:00", "16:29:00",3);

?>
    <section class="page-item timeTable" id="trainTimeTable">
        <article class="ac">
        <header>
            <h3>Trainingszeiten</h3>
        </header>
        <table class="timetable">
            <tr>
                <th></th>
                <th colspan="2">Montag</th>
                <th colspan="3">Dienstag</th>
                <th colspan="2">Mittwoch</th>
                <th colspan="3">Donnerstag</th>
                <th colspan="2">Freitag</th>
                <th>Samstag</th>
            </tr>
            <tr>
                <td class="subheadline">Uhrzeit</td>
                <td class="subheadline">Feld 1</td>
                <td class="subheadline">Feld 2</td>
                <td class="subheadline">Feld 1</td>
                <td class="subheadline">Feld 2</td>
                <td class="subheadline">Halle</td>
                <td class="subheadline">Feld 1</td>
                <td class="subheadline">Feld 2</td>
                <td class="subheadline">Feld 1</td>
                <td class="subheadline">Feld 2</td>
                <td class="subheadline">Halle</td>
                <td class="subheadline">Feld 1</td>
                <td class="subheadline">Feld 2</td>
                <td class="subheadline">Halle</td>
            </tr>
            <?php for($i=0;$i<5;$i++) { 
					$startHour = 16+$i;
					$startMinutes = 0;
					$endHour = 16+$i;
					$endMinutes = 30;
					
					$startTime = $startHour.":".$startMinutes.":00"; 
					$endTime = $endHour.":".$endMinutes.":00"; 
					
					ModSvwTimetableHelper::displayTimeTableRow($from, $to, $startTime, $endTime);	
					
					$startMinutes = 30;
					$endHour++;
					$endMinutes = 0;
					
					$startTime = $startHour.":".$startMinutes.":00"; 
					$endTime = $endHour.":".$endMinutes.":00"; 
					$endHour--;
					ModSvwTimetableHelper::displayTimeTableRow($from, $to, $startTime, $endTime);	
					
				} ?>
            <tr>
				<td class="footer" colspan="14">Stand: 19.11.2012</td>
			</tr>
        </table>
        </article>
    </section>
