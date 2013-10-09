<div class="index" style="width: 60%;">

<p>Welcome to Llechfan.</p>
<p>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ross
 * Date: 10/01/13
 * Time: 17:18
 * To change this template use File | Settings | File Templates.
 */

foreach($properties as $property)
{
   if($property['Property']['property'] == "homepage_text") echo h($property['Property']['value']);
}

?>
</p>
<p id="availability_info">

</p>
<div id="availability_data">
</div>
</div>
<div class="sidebar">
    <h3><?php echo __('Available Accomodation'); ?></h3>
    <ul>
        <div id="availabilitychecker"></div>
        <li><?php echo $this->Html->link(__('Make a Booking'), array('controller' => 'bookings', 'action' => 'add')); ?></li>

    </ul>

</div>
<?php

?>
<script>
    var monthData = null;
    var lastMonth = 0;
    var dataLoaded = false;
    var colourRating = '';
    var enableSelect = true;
    var timeStamp;
    var toolTip = '';

    function onChangeMonth(year, month) {
        dataLoaded = false;
        monthData = null;
        $.ajax({
            async: false,
            url: 'bookings/jsonAvailabilityData/calMonth:' + month + '/calYear:' + year,
            dataType: "json",
            success: function(data) {
                  monthData = data;
                  dataLoaded = true;
                  lastMonth = month;
            }
        });
    }

    $('#availabilitychecker').datepicker({

        onSelect: function(dateText) {
            $('#availability_info').load('bookings/ajaxAllocationInfo/calDate:'
                    + new Date(dateText).getTime());
        },
        onChangeMonthYear: function(year, month, inst) {
            onChangeMonth(year, month);
        },
        beforeShowDay: function (date) {
            dateObj = new Date(Date.parse(date));
            if(!dataLoaded || dateObj.getMonth() != lastMonth )
            {
                onChangeMonth(dateObj.getFullYear(), dateObj.getMonth() + 1);
                lastMonth = dateObj.getMonth();
            }
            dayVal = dateObj.getDate();
            monthVal = parseInt(dateObj.getMonth()) + 1;
            if(dayVal.toString().length < 2) dayVal = '0' + dayVal;
            if(monthVal.toString().length < 2) monthVal = '0' + monthVal;
            timeStamp = dateObj.getFullYear() + "-" + monthVal + "-" + dayVal;
            if(monthData != undefined) {
            $.each(monthData, function(index) {
                //alert(timeStamp + ',' + index);
                if(timeStamp == index)
                {
                     colourRating = monthData[index].calColourRating;
                     toolTip = 'Beds Available: '
                            + monthData[index].bedsAvailable + 'Beds Taken: ' +
                             monthData[index].bedsTaken;
                    if(colourRating == 'red')
                    {
                        enableSelect = false;
                    }  else {
                        enableSelect = true;
                    }
                    //alert( colourRating );
                }
            });
                return [enableSelect, colourRating, toolTip ];
            }

            //return [true, "", ""];
        }
    });
</script>
