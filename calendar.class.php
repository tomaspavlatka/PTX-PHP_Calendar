<?php
/**
 * Author: Tomas Pavlatka (tomas.pavlatka@gmail.com)
 * Created: 2010-12-28
 */
    
class PTX_Calendar {

    /**
    * Calendar type
    * 1 - CAL_GREGORIAN    
    */     
    private $_calendar = '1';
    
    /**
     * Date format.
     */          
    private $_dateFormat = 'Y-m-d';
    
    
    /**
     * First day of week
     * 1 - Monday, 7 - Sunday     
     */         
    private $_firstDayOfWeek = 1;

    /**
     * Options.
     */         
    private $_options;
    
    /**
     * Show days from different months.
     * 
     * flag whether days from different month should be shown.
     */                        
    private $_showDaysFromDifferentMonth = false;
    
    /**
     * Construct.
     * 
     * constructor of class
     */                   
    public function __construct(array $options = array()) {
        // Options.
        $this->_options = $options;
        
        // Set variables.
        $this->_setVariables();
    }
    
    /**
     * Build month
     * 
     * build calendar for specific month.
     * @param $month - month
     * @param $year - year
     * @return array with strftime for each day separated by week
     */                                   
    public function buildMonth($month,$year) {
        
        // Complete dates.
        $dateRange = $this->_completeDates($month,$year);
        
        // Dates.
        $dates = $this->_getDatesInArray($dateRange['start_date'],$dateRange['end_date']);
        
        // Array with dates.
        return $this->_buildWeekArray($dates);
    }
    
    /**
     * Build week array.
     * 
     * groups dates according to week
     * @param $dates - array with dates
     * @return array with dates according to week
     */                              
    private function _buildWeekArray(array $dates) {
        // Variables.
        $tableRows = array();
        $i = $counter = 0;
        
        // Foreach.
        foreach($dates as $date) {
            if($counter != 0 && $counter % 7 == 0) {
                $i++;
            }
            
            $tableRows[$i][] = $date;
            $counter++;
        }
        
        return $tableRows;
    }
    
    /**
     * Complete dates.
     * 
     * completes dates to fill all columns in table.
     * @param $month - month
     * @param $year - year
     * @return array('start_date'=>{date},'end_date'=>{date})
     */                                   
    private function _completeDates($month, $year) {
        // Varibles.
        $firstDay = $this->_numberOfFirstDay($month,$year);
        $lastDay = $this->_numberOfLastDay($month,$year);
        $countOfDays = $this->_countDaysInMonth($month,$year);
        
        // Count start + end dates.
        $startDate = (int)(2-$firstDay)+($this->_firstDayOfWeek-1);
        $endDate = (int)($countOfDays+(7-$lastDay)+($this->_firstDayOfWeek-1));
        
        // Correct start date.
        if($startDate > 1) {
            $startDate -= 7;
        }
        
        // Correct end date.
        if($endDate > ($countOfDays + 7)) {
            $endDate -= 7;
        }
        
        // Count start + end date.
        $array = array(
            'start_date' => mktime(0,0,0,(int)$month,$startDate,(int)$year),
            'end_date' => mktime(0,0,0,(int)$month,$endDate,(int)$year), 
        );
        
        return $array;
    }
    
    /**
     * Count days in month.
     * 
     * counts days in specific month
     * @return number of days in specific month
     */                       
    private function _countDaysInMonth($month,$year) {
        if($this->_calendar == 1) {
            return  cal_days_in_month(CAL_GREGORIAN,(int)$month,(int)$year);
        }
    }
    
    /**
     * Get dates in array.
     * 
     * returns all date between $startDate and $endDate
     * @param $startDate - start date
     * @param $endDate - end date    
     * @param $format - format of dates 
     * @return array of dates 
     */   
    private function _getDatesInArray($startDate, $endDate) {
        // Variables.
        $return = array($startDate);
        $start = $startDate;
        $i=1;
     
        // Magic body.    
        if($startDate < $endDate) {
            while($start < $endDate) {
                $start = strtotime(date('Y-m-d',$startDate).'+'.$i++.' days');
                $return[] = $start;
            }
        }
     
        // Return.
        return $return;
    }
    
    /**
     * Number of first day.
     * 
     * returns number of first day for specific month
     * @param $month - month
     * @param $year - year
     * @return result of date('N')
     */                             
    private function _numberOfFirstDay($month,$year) {
        
        // Return.
        return date('N',mktime(0,0,0,(int)$month,1,(int)$year));
    }
    
    /**
     * Number of first day.
     * 
     * returns number of first day for specific month
     * @param $month - month
     * @param $year - year
     * @return result of date('N')
     */                             
    private function _numberOfLastDay($month,$year) {
        // Count days.
        $countDays = $this->_countDaysInMonth($month, $year);
        
        // Return.
        return date('N',mktime(0,0,0,(int)$month,$countDays,(int)$year));
    }
    
    /**
     * Set variables.
     * 
     * set up variables.
     */                    
    private function _setVariables() {
        // Calendar.
        if(isset($this->_options['calendar'])) {
            $this->_calendar = $this->_options['calendar'];
        }
        
        // Date format.
        if(isset($this->_options['date_format'])) {
            $this->_dateFormat = $this->_options['date_format'];
        }
        
        // First day of week.
        if(isset($this->_options['first_day_of_week'])) {
            $this->_firstDayOfWeek = $this->_options['first_day_of_week'];
        }
        
        // Show days from different month.
        if(isset($this->_options['show_days_from_different_month'])) {
            $this->_showDaysFromDifferentMonth = $this->_options['show_days_from_different_month'];
        }
    }
}