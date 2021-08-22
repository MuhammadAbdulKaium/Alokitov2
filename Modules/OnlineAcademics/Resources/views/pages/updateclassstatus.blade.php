				<span>
					<strong> CLass Status:
					  </strong>
				</span>	 
				<span>
				  	<strong style="color: green"> 
				  	@if(isset($ScheduledData)) 
                    <input type="hidden" id="class_status" name="class_status" value="{{ $ScheduledData->class_status }}">

                         @if($ScheduledData->class_status ==2 ) 
                                {{ 'Ongoing' }} 
                         
                        @elseif($ScheduledData->class_status ==6 ) 
                        
                               {{ 'Started' }} 
                        @endif 
                    @endif 
                 </strong>
                </span>
                <br/>	
				<span>
					<strong> Teacher Status :
					  </strong>
				</span>	 
				  <span>
				  	<strong style="color: green"> 
				  	@if(isset($ScheduledData)) 
                        
                         @if($ScheduledData->class_status ==2 ) 
                                {{ 'Not Live' }} 
                         
                        @elseif($ScheduledData->class_status ==6 ) 
                        
                               {{ 'Live' }} 
                        @endif 
                    @endif 
                 </strong>
                </span>	

                <span>
					<strong> CLass Start Time:
					  </strong>
				</span>	 
				  <span>
				  	<strong style="color: green"> 
				  @if(isset($ScheduledData))                     
                        @if($ScheduledData->class_status ==6 ) 
                            @if(isset($ScheduledData->class_conduct_time))
                                {{ date('h:i a',strtotime($ScheduledData->class_conduct_time)) }}
                             @endif   
                        @else
                             {{ 'Not set' }}
                       @endif 
                   @endif 
                 </strong>
                </span>	
        